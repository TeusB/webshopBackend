<?php
session_start();
if (isset($_SESSION['idUser'])){
    print $_SESSION['idUser'];
} else {
    print false;
}