<?php
session_start();
if(!isset($_SESSION['user_id'])){
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Information Fill Section</title>
  <link rel="stylesheet" href="..\css\style.css">
</head>
<body>
  <form action="save_information.php" method="POST" enctype="multipart/form-data">
  <div class="form-container">
    <!--Child information fill section-->
    <h2>Child Information</h2>
    <div class="field-group">
      <input type="text" name="child_full_name" placeholder="Name in full" required>
    </div>
    <div class="field-group">
      <input type="text" name="child_initials" placeholder="Name with initials">
    </div>
    <div class="field-group">
      <input type="text" name="child_religion" placeholder="Religion">
    </div>
    <div class="field-group">
      <input type="date" name="dob"  placeholder="Date Of Birth" required>
    </div>
    <div class="field-group">
      <input type="number" name="age" placeholder="Age" >
    </div>

    <!--Applicant information fill section-->
    <h2>Applicant Information</h2>
    <div class="field-group">
      <input type="text" name="applicant_full_name" placeholder="Name in full" required>
    </div>
    <div class="field-group">
      <input type="text" name="applicant_initials" placeholder="Name with initials">
    </div>
    <div class="field-group">
      <input type="text" name="applicant_nic" placeholder="NIC Number">
    </div>
    <div class="field-group">
      <input type="text" name="applicant_religion" placeholder="Religion" >
    </div>
    <div class="field-group">
      <input type="text" name="applicant_address" placeholder="Address">
    </div>
    <div class="field-group">
      <input type="text" name="applicant_phone" placeholder="Telephone Number">
    </div>
    <div class="field-group">
      <input type="text" name="resident_district" placeholder="Resident District">
    </div>

    <!--spouse information fill section-->
    <h2>Spouse Information</h2>
    <div class="field-group">
      <input type="text" name="spouse_full_name" placeholder="Name in full" >
    </div>
    <div class="field-group">
      <input type="text" name="spouse_initials" placeholder="Name with initials" >
    </div>
    <div class="field-group">
      <input type="text" name="spouse_nic" placeholder="NIC Number" >
    </div>
    <div class="field-group">
      <input type="text" name="spouse_religion" placeholder="Religion" >
    </div>
    <div class="field-group">
      <input type="text" name="spouse_address" placeholder="Address" >
    </div>
    <div class="field-group">
      <input type="text" name="spouse_phone" placeholder="Telephone Number" >
    </div>
    <div class="field-group">
      <input type="text" name="spouse_district" placeholder="Resident District" >
    </div>

    <h2>Evidence Information</h2>
    <label>Electrical Bills: </label>
    <input type="file" name="ebill[]" multiple><br><br>
    
    <h2>Living Information</h2>
    <label>Living Files: </label>
    <input type="file" name="lbill[]" multiple><br><br>

    <button type="submit">Submit</button>
  </div>
  </form>
</body>
</html>
