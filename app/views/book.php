<div class="container mt-4">
    <h1>Rezervă o Cameră</h1>
    <div id="booking-steps">
        <div id="step1">
            <h2>Selectează Datele</h2>
            <label for="start-date">Data Începerii:</label>
            <input type="date" id="start-date" class="form-control">
            <label for="end-date">Data Încheierii:</label>
            <input type="date" id="end-date" class="form-control">
            <button class="next-step btn btn-primary mt-3">Următorul</button>
        </div>
        <div id="step2" style="display:none;">
            <h2>Selectează Oaspeții</h2>
            <label for="guests">Numărul de Oaspeți:</label>
            <input type="number" id="guests" class="form-control">
            <button class="prev-step btn btn-secondary mt-3">Anterior</button>
            <button class="next-step btn btn-primary mt-3">Următorul</button>
        </div>
        <div id="step3" style="display:none;">
            <h2>Camere Disponibile</h2>
            <div class="row" id="available-rooms">
            </div>
            <button class="prev-step btn btn-secondary mt-3">Anterior</button>
            <button class="next-step btn btn-primary mt-3" disabled>Următorul</button>
        </div>
        <div id="step4" style="display:none;">
            <h2>Selectează Adăugiri</h2>
            <div class="row" id="available-addons">
            </div>
            <button class="prev-step btn btn-secondary mt-3">Anterior</button>
            <button class="next-step btn btn-primary mt-3">Următorul</button>
        </div>
        <div id="step5" style="display:none;">
            <h2>Revizuiește Rezervarea</h2>
            <div id="review-booking">
            </div>
            <div class='d-flex align-items-center gap-2'>
                <button class="prev-step btn btn-secondary mt-3">Anterior</button>
                <div id='final-actions'>
                    <button id="finalize-booking" class="btn btn-success mt-3">Finalizează Rezervarea</button>
                </div>
            </div>
        </div>
    </div>
</div>