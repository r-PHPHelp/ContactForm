<?php

$data = [
    "name"    => $_GET["name"] ?? null,
    "email"   => "From:".($_GET["email"] ?? null),
    "message" => $_GET["message"] ?? null,
    "deliver" => "youremail@email.com",
    "subject" => "New Message"
];
$data["body"] = "From: {$data["name"]} \r\n Message: {$data["message"]}";

if (!$data["name"] || !$data["email"] || !$data["message"]) {
    echo "You must submit a name, email address & message!";
} elseif (!mail($data["deliver"], $data["subject"], $data["body"], $data["email"])) {
    echo "Failed to send email!";
} else {
    echo "Email sent!";
}
