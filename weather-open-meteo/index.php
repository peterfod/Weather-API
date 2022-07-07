<?php
include 'open-meteo.php';
?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../homepink.ico">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Open-Meteo</title>
</head>

<body>
    <div class="container">
        <!-- Current -->
        <div class="card border-info mb-3 text-center" style="max-width: 10rem;">
            <div class="card-header text-info fw-bold">Budapest</div>
            <div class="card-body pt-1">
                <?php
                echo '<img src="icons/' . $current['icon'] . '.png" alt="icon" width="64">';
                ?>
                <h3 class="card-title text-danger">
                    <?php echo $current['temperature'] ?> °C
                </h3>
                <p class="card-text"><img class="pe-2 pb-1" src="icons/icon-wind.png" alt="icon" width="32">
                    <?php echo $current['windspeed'] ?> km/h
                </p>
                <p class="card-text"><img class="pe-2 pb-1" src="icons/icon-sunrise-color.png" alt="icon" width="32">
                    <?php echo $current['sunrise'] ?>
                </p>
                <p class="card-text"><img class="pe-2 pb-1" src="icons/icon-sunset-color.png" alt="icon" width="32">
                    <?php echo $current['sunset'] ?>
                </p>
            </div>
        </div>
        <div class="row ms-0">
            <!-- Daily -->
            <?php
            if ($restapi) {
                $napok   = array(
                    "V", "H", "K",  "Sze",
                    "Cs", "P", "Szo"
                );
                $napokSzama = count($daily);
                for ($i = 0; $i < $napokSzama; $i++) {
                    echo '
                    <div class="card border-light mb-3 text-center me-2 p-0" style="max-width: 8rem;">
                        <div class="card-header fw-bold">' . (int)substr($daily[$i]->date, 8, 2) . '<br>' . $napok[date("w", strtotime($daily[$i]->date))] . '</div>
                        <div class="card-body pt-1">
                        <img src="icons/' . $daily[$i]->icon . '.png" alt="icon" width="48">
                        <h5 class="card-title text-danger">' . $daily[$i]->maxTemp . ' °C</h5>
                        <h5 class="card-title text-primary">' . $daily[$i]->minTemp . ' °C</h5>
                        <p class="card-text"><img class="pe-2 pb-1" src="icons/icon-wind.png" alt="icon" width="26">' . $daily[$i]->windspeed . ' km/h</p>
                        <p class="card-text"><img class="pe-2 pb-1" src="icons/icon-umbrella.png" alt="icon" width="26">' . $daily[$i]->precipitation_sum . ' mm</p>
                        <p class="card-text"><img class="pe-2 pb-1" src="icons/icon-sunrise-color.png" alt="icon" width="26">' . $daily[$i]->sunrise . '</p>
                        <p class="card-text"><img class="pe-2 pb-1" src="icons/icon-sunset-color.png" alt="icon" width="26">' . $daily[$i]->sunset . '</p>
                        </div>
                    </div>';
                }
            } else {
                echo "<div>$error</div>";
            }
            ?>
        </div>
        <!-- Hourly -->
        <div class="table-responsive">
            <table class="table text-center align-middle" style="width: 40rem;">
                <thead>
                    <tr class="table-dark">
                        <th>Óra</th>
                        <th>Időjárás</th>
                        <th>Hőmérséklet</th>
                        <th>Szél</th>
                        <th>Eső</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    if ($restapi) {
                        for ($i = 0; $i < count($hourly); $i++) {
                            echo '
                            <tr class="table-active">
                                <td>' . $hourly[$i]->time . '</td>
                                <td><img src="icons/' . $hourly[$i]->icon . '.png" alt="icon" width="48px"></td>
                                <td>' . $hourly[$i]->temp . ' °C</td>
                                <td><img class="pe-2" src="icons/icon-wind.png" alt="icon" width="32px"> ' . $hourly[$i]->windspeed . ' km/h</td>
                                <td><img class="pe-2" src="icons/icon-umbrella.png" alt="icon" width="32px"> ' . $hourly[$i]->precipitation . ' mm</td>
                            </tr>';
                        }
                    } else {
                        echo "<div>$error</div>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>