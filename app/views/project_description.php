<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Description</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container p-4">
        <h1 class='text-center'>Descriere Proiect DAW</h1>
        <p>Activitatile unui hotel: Hotel X. Acest proiect este conceput pentru a ajuta la gestionarea rezervarilor de camere si a camerelor.</p>
        <h2>Prezentare Generala</h2>
        <p>Hotel X este un hotel fictiv care dispune de o aplicatie web pentru rezervarea camerelor. Utilizatorii pot vizualiza camerele disponibile, pot face rezervari si pot gestiona rezervarile existente.</p>
        <h2>Roluri</h2>
        <p>In aplicatie utilizatorii pot avea doua roluri: <b>customer</b> si <b>admin</b>.</p>
        <p>Utilizatorii primesc automat rolul de <b>customer</b> la inregistrare</p>  
        <img src="/images/prezentare2.png" class="d-block w-auto" alt="Imagine Cameră" style="max-width: 100%; ">
        <h2>Flow principal</h2>
        <p>Flowul principal consta in 5 pasi pe care utilizatorul trebuie sa-i completeze cu success pentru a putea rezerva o camera.</p>
        <img src="/images/prezentare.png" class="d-block w-auto" alt="Imagine Cameră" style="max-width: 100%; ">

        <h2>Baza de date</h2>
        <p>Entitati:</p>
        <ul>
            <li><strong>users</strong>: Stocheaza informatii despre utilizatori, inclusiv email, nume, parola, moneda preferata, rol si data crearii.</li>
            <li><strong>rooms</strong>: Stocheaza informatii despre camere, inclusiv nume, descriere, URL imagine, pret pe noapte, capacitate, etaj, popularitate si numarul de camere disponibile.</li>
            <li><strong>bookings</strong>: Stocheaza informatii despre rezervari, inclusiv ID-ul utilizatorului, ID-ul camerei, datele de check-in si check-out, adaugiri, pret total si status.</li>
            <li><strong>facilities</strong>: Stocheaza informatii despre facilitatile disponibile in hotel.</li>
            <li><strong>room_facilities</strong>: Leaga camerele de facilitatile disponibile.</li>
            <li><strong>addons</strong>: Stocheaza informatii despre addonsurile disponibile pentru rezervari.</li>
            <li><strong>analytics</strong>: Stocheaza informatii despre analytics.</li>
        </ul>
    </div>
</body>
</html>