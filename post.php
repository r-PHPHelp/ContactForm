<?php

require getcwd().'/GUMP-master/gump.class.php';

$gump = new GUMP;
$data = $gump->sanitize($_POST);
$gump->validation_rules([
    'name'    => 'required|valid_name',
    'email'   => 'required|valid_email',
    'message' => 'required'
]);
$gump->filter_rules([
    'name'    => 'trim|sanitize_string',
    'email'   => 'trim|sanitize_email',
    'message' => 'trim|sanitize_string'
]);
$data = $gump->run($data);
if($data === false) {
	echo $gump->get_readable_errors(true);
} else {

  $data = [
      "name"      => $data["name"],
      "email"     => "From:".($data["email"]),
      "message"   => $data["message"],
      "photo"     => $_FILES["photo"] ?? null,
      "deliver"   => "youremail@email.com",
      "subject"   => "New Message",
      "filePath"  => $_FILES["photo"] ? getcwd()."/".$_FILES["photo"]["name"] : null,
  ];
  $data["body"] = "From: {$data["name"]} \r\n ImageName: {$data["photo"]["name"]} \r\n Message: {$data["message"]}";

  if (!$data["email"]) {
    echo "You must submit an email address!";
  } elseif (!$data["filePath"] || !move_uploaded_file($data["photo"]["tmp_name"], $data["filePath"])) {
    echo "Upload failed!";
  } elseif (!mail($data["deliver"], $data["subject"], $data["body"], $data["email"])) {
    echo "Failed to send!";
  } else {
    echo "Email sent!";
  }
}
