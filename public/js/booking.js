document.addEventListener('DOMContentLoaded', function () {
    let currentStep = 1;
    const urlParams = new URLSearchParams(window.location.search);
    const stepParam = urlParams.get('step');
    if (stepParam) {
        currentStep = parseInt(stepParam);
    }

    let bookingData = JSON.parse(localStorage.getItem('bookingData')) || {};

    function showStep(step) {
        for (let i = 1; i <= 5; i++) {
            document.getElementById(`step${i}`).style.display = (i === step) ? 'block' : 'none';
        }
        if (step === 3) {
            fetchAvailableRooms();
        } else if (step === 4) {
            fetchAddons();
        } else if (step === 5) {
            reviewBooking();
        }
    }

    function validateStep(step) {
        if (step === 1) {
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;
            const today = new Date().setHours(0, 0, 0, 0);
            if (!startDate || !endDate) {
                alert('Please select both start date and end date.');
                return false;
            }
            if (new Date(startDate).setHours(0, 0, 0, 0) < today) {
                alert('Start date cannot be in the past.');
                return false;
            }
            if (new Date(endDate) < new Date(startDate)) {
                alert('End date cannot be before start date.');
                return false;
            }
            bookingData.startDate = startDate;
            bookingData.endDate = endDate;
            document.querySelector('#step1 .next-step').disabled = false; // Enable the next button after validation
        } else if (step === 2) {
            const guests = parseInt(document.getElementById('guests').value, 10);
            if (isNaN(guests) || guests <= 0 || guests > 12) {
                alert('Please enter a valid number of guests (1-12).');
                return false;
            }
            bookingData.guests = guests;
        } else if (step === 3) {
            const selectedRoom = document.querySelector('input[name="selected_room"]:checked');
            if (!selectedRoom) {
                alert('Please select a room.');
                return false;
            }
            bookingData.roomId = selectedRoom.value;
        } else if (step === 4) {
            const selectedAddons = Array.from(document.querySelectorAll('input[name="selected_addons"]:checked')).map(cb => cb.value);
            bookingData.addons = selectedAddons;
        }
        localStorage.setItem('bookingData', JSON.stringify(bookingData));
        return true;
    }

    function fetchAvailableRooms() {
        const { startDate, endDate, guests } = bookingData;
        fetch(`/api/rooms/available?start_date=${startDate}&end_date=${endDate}&guests=${guests}`)
            .then(response => response.json())
            .then(data => {
                const availableRoomsDiv = document.getElementById('available-rooms');
                availableRoomsDiv.innerHTML = '';
                if (data.rooms.length === 0) {
                    availableRoomsDiv.innerHTML = '<p>No rooms available for the selected dates and number of guests.</p>';
                    document.querySelector('.next-step').disabled = true;
                } else {
                    document.querySelector('.next-step').disabled = true; // Initially disable the next button
                    data.rooms.forEach(room => {
                        const roomDiv = document.createElement('div');
                        roomDiv.className = 'col-md-6 mb-2'; // Make rows more compact
                        roomDiv.innerHTML = `
                            <div class="card" style="height: 100%;">
                                <img src="${room.image_url}" class="card-img-top" alt="${room.name}" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">${room.name}</h5>
                                    <p class="card-text m-0">${room.description}</p>
                                    <p class="card-text m-0"><strong>Capacity:</strong> ${room.capacity} people</p>
                                    <p class="card-text m-0"><strong>Floor:</strong> ${room.floor}</p>
                                    <p class="m-0"><strong>Facilities:</strong> ${room.facilities || 'N/A'}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="card-text m-0"><strong>Price per night:</strong> $${room.price_per_night}</p>
                                        <input type="checkbox" name="selected_room" value="${room.id}" class="select-room-checkbox" style="transform: scale(1.5);"> <!-- Make checkbox bigger -->
                                    </div>
                                </div>
                            </div>
                        `;
                        availableRoomsDiv.appendChild(roomDiv);
                    });

                    document.querySelectorAll('.select-room-checkbox').forEach(checkbox => {
                        checkbox.addEventListener('change', function () {
                            document.querySelectorAll('.select-room-checkbox').forEach(cb => {
                                if (cb !== checkbox) cb.checked = false;
                            });
                            document.querySelector('#step3 .next-step').disabled = false; // Enable the next button when a room is selected
                        });
                    });
                }
            })
            .catch(error => console.error('Error fetching available rooms:', error));
    }

    function fetchAddons() {
        fetch('/api/addons')
            .then(response => response.json())
            .then(data => {
                const addonsDiv = document.getElementById('available-addons');
                addonsDiv.innerHTML = '';
                data.addons.forEach(addon => {
                    const addonDiv = document.createElement('div');
                    addonDiv.className = 'col-md-6 mb-2';
                    addonDiv.innerHTML = `
                        <div class="card" style="height: 100%;">
                            <div class="card-body">
                                <h5 class="card-title">${addon.name}</h5>
                                <p class="card-text"><strong>Price:</strong> $${addon.price}</p>
                                <input type="checkbox" name="selected_addons" value="${addon.id}" class="select-addon-checkbox" style="transform: scale(1.5);"> <!-- Make checkbox bigger -->
                            </div>
                        </div>
                    `;
                    addonsDiv.appendChild(addonDiv);
                });
            })
            .catch(error => console.error('Error fetching addons:', error));
    }

    function reviewBooking() {
        const { startDate, endDate, guests, roomId, addons } = bookingData;
        const nights = Math.ceil((new Date(endDate) - new Date(startDate)) / (1000 * 60 * 60 * 24));

        if (!window.hotelx_uname) {
            const reviewDiv = document.getElementById('final-actions');
            reviewDiv.innerHTML = `
                <p>Please <a href="/login" id="login-link">login</a> or <a href="/register" id="register-link">register</a> to finalize your booking.</p>
            `;

            document.getElementById('login-link').addEventListener('click', function () {
                localStorage.setItem('currentStep', 5);
            });

            document.getElementById('register-link').addEventListener('click', function () {
                localStorage.setItem('currentStep', 5);
            });

            return;
        }

        Promise.all([
            fetch(`/api/room?id=${roomId}`).then(response => response.json()),
            fetch(`/api/addons`).then(response => response.json()),
        ]).then(([roomData, addonsData]) => {
            const room = roomData.room;
            const selectedAddons = addonsData.addons.filter(addon => addons.includes(addon.id.toString()));
            console.log({ nights })
            const addonsCost = selectedAddons.reduce((total, addon) => total + parseFloat(addon.price), 0);
            const totalCost = (room.price_per_night * nights) + addonsCost;
            console.log({ addonsCost, totalCost })
            fetch(`/api/convertAmount?amount=${totalCost}&currency=${window.hotelx_preferred_currency}`)
            .then(response => response.json())
            .then(data => {
                console.log({data})
                const convertedTotalCost = data.convertedAmount;

                const reviewDiv = document.getElementById('review-booking');
                reviewDiv.innerHTML = `
                    <p><strong>Name:</strong> ${window.hotelx_uname}</p>
                    <p><strong>Period:</strong> ${startDate} to ${endDate} (${nights} nights)</p>
                    <p><strong>Guests:</strong> ${guests}</p>
                    <p><strong>Room:</strong> ${room.name}</p>
                    <p><strong>Addons:</strong> ${selectedAddons.map(addon => addon.name).join(', ')}</p>
                    <p><strong>Total Cost:</strong> ${convertedTotalCost}</p>
                `;
            })
            .catch(error => console.error('Error fetching converted amount:', error));
        }).catch(error => console.error('Error fetching review data:', error));
    }

    function finalizeBooking() {
        const { step: _, ...data } = bookingData
        fetch('/api/book', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Booking finalized!');
                    localStorage.removeItem('bookingData');
                    window.location.href = '/';
                } else {
                    alert('Error finalizing booking: ' + data.error);
                }
            })
            .catch(error => console.error('Error finalizing booking:', error));
    }

    showStep(currentStep);

    document.getElementById('booking-steps').addEventListener('click', function (event) {
        if (event.target.classList.contains('next-step')) {
            if (validateStep(currentStep)) {
                currentStep++;
                showStep(currentStep);
            }
        } else if (event.target.classList.contains('prev-step')) {
            currentStep--;
            showStep(currentStep);
        } else if (event.target.id === 'finalize-booking') {
            if (validateStep(currentStep)) {
                finalizeBooking();
            }
        }
    });
});
