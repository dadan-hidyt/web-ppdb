<?php

User::getInstance()->logout();
header("location:".base_url('login'));
?>