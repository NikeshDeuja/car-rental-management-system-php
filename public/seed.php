<?php
declare(strict_types=1);
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";

$adminEmail = "admin@starcar.com";

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$adminEmail]);
if (!$stmt->fetch()) {
  $hash = password_hash("admin123", PASSWORD_DEFAULT);
  $pdo->prepare("INSERT INTO users(full_name,email,password_hash,role) VALUES (?,?,?,'admin')")
      ->execute(["Starcar Admin", $adminEmail, $hash]);
}

$cars = [
  ["Toyota","Yaris",2022,"Hatchback","White",5,"Automatic","Petrol",4500,"assets/cars/car_1769939154_4514.jpg"],
  ["Hyundai","Creta",2023,"SUV","Grey",5,"Automatic","Petrol",7500,"assets/cars/car_1769939426_2447.jpg"],
  ["Kia","Seltos",2024,"SUV","Black",5,"Automatic","Petrol",8200,"assets/cars/car_1769939472_4915.jpg"],
  ["Honda","Civic",2021,"Sedan","Blue",5,"Automatic","Petrol",6800,"assets/cars/car_1769939488_6736.jpg"],
  ["Suzuki","Swift",2020,"Hatchback","Red",5,"Manual","Petrol",3900,"assets/cars/car_1769939506_9092.jpg"],
  ["Mahindra","XUV700",2024,"SUV","Silver",7,"Automatic","Diesel",9800,"assets/cars/car_1769939571_4203.jpg"],
];

$count = 0;
try {
  $missing = 0;
  foreach ($cars as $c) {
    // standard unpacking

    list($make,$model,$year,$type,$color,$seats,$trans,$fuel,$price,$img) = $c;
    


    $check = $pdo->prepare("SELECT id FROM cars WHERE make=? AND model=? AND year=?");
    $check->execute([$make,$model,$year]);
    if ($check->fetch()) continue;

    $pdo->prepare("INSERT INTO cars(make,model,year,car_type,color,seats,transmission,fuel,price_per_day_nrp,image_path,is_active)
                   VALUES (?,?,?,?,?,?,?,?,?,?,1)")
        ->execute([$make,$model,$year,$type,$color,$seats,$trans,$fuel,$price,$img]);
    $count++;
  }

  echo "<div style='font-family:Arial;padding:18px;background:#eaffea;border:1px solid #0a0'>
    <h2>Seed complete ✅</h2>
    <p>Inserted new cars: <b>{$count}</b></p>
    <p>Admin login: <b>admin@starcar.com</b> / <b>admin123</b></p>
    <p><a href='../login.php'>Go to Login</a></p>
  </div>";

} catch (Exception $e) {
  echo "<div style='font-family:Arial;padding:18px;background:#ffeaea;border:1px solid #d00'>";
  echo "<h2>Seed Failed ❌</h2>";
  echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
  echo "<p><b>Possible Fix:</b> Have you run the <a href='../setup.php'>Setup Script</a> yet?</p>";
  echo "</div>";
}
