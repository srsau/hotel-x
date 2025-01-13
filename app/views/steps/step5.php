<div class="container mt-4">
<?php if (isset($stepper)) echo $stepper; ?>
    <div id="step3">
        <h2>Revizuiește Rezervarea</h2>

        <?php if (isset($error) && $error): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/book?step=5">
        <input type="hidden" name="current_step" value="5">

            <div class="row" id="available-rooms">

                <?php if (!isset($_SESSION['user']['name'])): ?>
                    <p>Please <a href="/login" id="login-link">login</a> or <a href="/register" id="register-link">register</a> to finalize your booking.</p>
                <?php else: ?>


                    <p><strong>Name:</strong> <?= htmlspecialchars($_SESSION['user']['name']); ?></p>
                    <p><strong>Guests:</strong> <?= htmlspecialchars($_SESSION['booking']['data']['guests']); ?></p>
                    <p><strong>Addons:</strong>
                        <?php
                        if (!empty($_SESSION['booking']['data']['selected_addons'])):
                            $addonNames = array_map(function ($addon) {
                                return htmlspecialchars($addon['name']);
                            }, $_SESSION['booking']['data']['selected_addons']);
                            echo implode(', ', $addonNames);
                        else:
                            echo 'None';
                        endif;
                        ?>
                    </p>
                    <p><strong>Period:</strong> <?= htmlspecialchars($_SESSION['booking']['data']['start-date']); ?> to <?= htmlspecialchars($_SESSION['booking']['data']['end-date']); ?> (<?= htmlspecialchars($nights); ?> nopti)</p>
                    <p><strong>Room:</strong> <?= htmlspecialchars($_SESSION['booking']['data']['room']['name']); ?> </p>
                    <p><strong>Total Cost:</strong> <?= htmlspecialchars($convertedTotalCost); ?></p>
                    <!-- 
                    -->
                <?php endif; ?>

            </div>

            <a href="/book?step=<?= $step - 1 ?>" class="prev-step btn btn-secondary">Anterior</a>
           <?php if (isset($_SESSION['user']['id'])): ?>
            <button class="next-step btn btn-primary " type="submit">Finalizează Rezervarea</button>
            <?php endif; ?>
        </form>
        <form method="post" action="/reset-booking" class="mt-3">
            <button type="submit" class="btn btn-secondary">Resetare</button>
        </form>
    </div>
</div>