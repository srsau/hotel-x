<div class="container mt-4">
        <h1>Book a Room</h1>
        <div id="booking-steps">
            <div id="step1">
                <h2>Select Dates</h2>
                <label for="start-date">Start Date:</label>
                <input type="date" id="start-date" class="form-control">
                <label for="end-date">End Date:</label>
                <input type="date" id="end-date" class="form-control">
                <button class="next-step btn btn-primary mt-3">Next</button>
            </div>
            <div id="step2" style="display:none;">
                <h2>Select Guests</h2>
                <label for="guests">Number of Guests:</label>
                <input type="number" id="guests" class="form-control">
                <button class="prev-step btn btn-secondary mt-3">Previous</button>
                <button class="next-step btn btn-primary mt-3">Next</button>
            </div>
            <div id="step3" style="display:none;">
                <h2>Available Rooms</h2>
                <div class="row" id="available-rooms">
                    <!-- Available rooms will be dynamically loaded here -->
                </div>
                <button class="prev-step btn btn-secondary mt-3">Previous</button>
                <button class="next-step btn btn-primary mt-3" disabled>Next</button>
            </div>
            <div id="step4" style="display:none;">
                <h2>Select Addons</h2>
                <div class="row" id="available-addons">
                    <!-- Available addons will be dynamically loaded here -->
                </div>
                <button class="prev-step btn btn-secondary mt-3">Previous</button>
                <button class="next-step btn btn-primary mt-3">Next</button>
            </div>
            <div id="step5" style="display:none;">
                <h2>Review Booking</h2>
                <div id="review-booking">
                    <!-- Booking review will be dynamically loaded here -->
                </div>
                <div class='d-flex align-items-center gap-2'>

                    <button class="prev-step btn btn-secondary mt-3">Previous</button>
                    <div id='final-actions'>
                        <button id="finalize-booking" class="btn btn-success mt-3">Finalize Booking</button>
                    </div>
                </div>
            </div>
        </div>
    </div>