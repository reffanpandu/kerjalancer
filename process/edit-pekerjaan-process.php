<?php

include '../lib/connection.php';

if (isset($_POST['submit'])) {
    $namapekerjaan = $_POST["nama"];
    $kategoripekerjaan = $_POST["kategori"];
    $bataswaktu = $_POST["bataswaktu"];
    $salary = $_POST["salary"];
    $deskripsi = mysqli_real_escape_string($con, $_POST["deskripsi"]);
    $idjob = $_POST["idjob"];
    $waktusekarang = date('Y-m-d H:i:s');
    $skill = $_POST["skill"];
    $queryskill = mysqli_query($con, "SELECT * FROM job_has_skills WHERE id_job = '$idjob'");
    $querydeleteskill = "DELETE FROM job_has_skills WHERE id_job = '$idjob'";
    mysqli_query($con, $querydeleteskill);
    foreach ($skill as $s) {
        $queryinsertskill = "INSERT INTO job_has_skills VALUES ('$idjob', '$s')";
        mysqli_query($con, $queryinsertskill);
    }

    if ($bataswaktu >= $waktusekarang) {
        $query = "UPDATE job SET job_name = '$namapekerjaan', id_category = '$kategoripekerjaan', apply_expire_date = '$bataswaktu', job_salary = '$salary', job_description = '$deskripsi', job_update_date = '$waktusekarang' WHERE id_job = '$idjob'";
        mysqli_query($con, $query);
        header("Location: ../detail-pekerjaan-client.php?id=$idjob");
    } else {
        echo "<script>alert('Batas waktu tidak boleh kurang dari waktu sekarang!'); window.history.back();</script>";
    }
}

mysqli_close($con);
?>