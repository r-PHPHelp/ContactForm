<?php

use PHPMailer\PHPMailer\PHPMailer;

require getcwd().'/phpmailer/src/PHPMailer.php';
require getcwd().'/GUMP-master/gump.class.php';

class Form {
    
    protected $name,$email,$message,$directory,$photo,$deliver,$subject,$filePath,$body,$mailer;
    
    public function __construct($mailer, $validator)
    {
        $data = $validator->sanitize($_POST);
        $validator->validation_rules([
            'name'    => 'required|valid_name',
            'email'   => 'required|valid_email',
            'message' => 'required'
        ]);
        $validator->filter_rules([
            'name'    => 'trim|sanitize_string',
            'email'   => 'trim|sanitize_email',
            'message' => 'trim|sanitize_string'
        ]);
        $data = $validator->run($data);
        if ($data === false) {
          throw new \Exception($validator->get_readable_errors(true));
        }
        
        $this->name    = $data["name"];
        $this->email   = $data["email"];
        $this->message = $data["message"];
        $this->deliver = "youremail@email.com";
        $this->subject = "New Message";
        $this->body    = "From: {$this->name} \r\n ImageName: " . $this->photo["name"] ?? "No Image". " \r\n Message: {$this->message}";
        $this->mailer  = $mailer;
        $this->photo   = $_FILES["photo"] ?? null;
        if ($this->photo) {
            $this->filePath = getcwd()."/".$this->photo["name"];
        }
    }
    
    public function handleUpload() : Form
    {
        if (!$this->filePath || !move_uploaded_file($this->photo["tmp_name"], $this->filePath)) {
            throw new \Exception("Upload failed");
        }
        return $this;
    }
    
    public function mail() : Form
    {
        $this->mailer->From      = 'noreply@yoursite.com';
        $this->mailer->FromName  = 'System';
        $this->mailer->Subject   = $this->subject;
        $this->mailer->Body      = $this->body;
        $this->mailer->AddAddress($this->deliver);
        $this->mailer->AddAttachment($this->filePath, $this->photo["name"]);
        $this->mailer->Send();
        return $this;
    }
    
    public function delete() : void
    {
        if (!unlink($this->filePath)) {
            throw new \Exception("Delete failed");
        }
    }
}

try {
    $form = new Form(new PHPMailer(true), new GUMP);
    $form->handleUpload()->mail()->delete();
    echo "Email sent!";
} catch (\Exception $e) {
    echo $e->getMessage();
}
