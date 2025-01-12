<?php
function renderStepper($currentStep) {
    $steps = [
        1 => 'Selectează Datele',
        2 => 'Număr Guests',
        3 => 'Selectează Camera',
        4 => 'Addons',
        5 => 'Review'
    ];

    ob_start();
    ?>
    <style>
        .breadcrumb {
            display: flex;
            justify-content: space-between;
            background-color: transparent;
            padding: 10px 0;
        }
        .breadcrumb-item {
            flex: 1;
            text-align: center;
            position: relative;
            padding: 10px;
            margin: 0 5px;
            color: #6c757d;
            background-color: white;
            z-index: 1;
        }
        .breadcrumb-item.active {
            color: #007bff;
            font-weight: bold;
        }
        .breadcrumb-item.completed {
            color: #28a745;
            font-weight: bold;
        }
    </style>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <?php foreach ($steps as $step => $label): ?>
                <li class="breadcrumb-item <?= $step < $currentStep ? 'completed' : ($step == $currentStep ? 'active' : ''); ?>" <?= $step == $currentStep ? 'aria-current="page"' : ''; ?>>
                    <?= $step . ". " . htmlspecialchars($label); ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </nav>
    <?php
    return ob_get_clean();
}
?>