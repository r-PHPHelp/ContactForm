# Contact Form
Let’s talk about contact forms. As simple as this topic sounds, building one right (and finding a good example online), is not as easy as it sounds! The goal is to go over common use cases for contact forms, to explain each piece in simple terms, and provide step by step & complete examples. This project will rely solely on the kind hearted souls of the /r/PHPHelp subreddit.

## GET (simple)
The first contact form will be the simplest. Our “boss” wants us to build a simple contact form which collects and emails the following information: name, email, message. The first thing we will need to do is to setup our HTML form. Discussing how to setup a web server is out of scope here, so I’ll assume that is setup and you can access "get.html" on your machine.

So, let’s break down the HTML form (get.html). First, we have the "method" attribute. This attribute defines how we send the form data. Your options are "get" and "post". Explaining all the differences between GET and POST requests is out of scope, but it can be simplified for now. GET sends the form data as a query string, and POST sends the form data in the body of your request. Since this is a simple form, we’ll do a GET for now.

Second, we have the “action” attribute. This attribute defines where to send the form data. We’ll set "get.php" here, but you can use any absolute or relative URL.

Finally, we have some various input fields and a submit button. What’s import to note here is that only input fields with populated name attributes are sent as part of the submission, and that these values are used in the php file we’ll go over next.

Now let's look at our "get.php" file. Everything we need is packed into one array, then the return of the mail function is fed into a conditional for the success or fail message. The "$_GET" variable is one of many PHP superglobals, which are automagically set by PHP.

And that’s it! We have a working simple contact form!

## POST (simple)
The second form will be a little more complicated, because our "boss" decided that we need to allow our users to upload a picture of themselves as well! We don’t need to email the picture right now, just upload it to our server and send its location via email.

Let's first review "post.html". The GET method is limited in what kind of data it can send, and now that we are dealing with files, we can no longer use it. So, we switch to the POST method and need to define a new encoding type. Without a file upload the default is fine, so you don’t always need to specify an enctype. We’ve also changed the action to our new php file.

Reviewing the "post.php" file, we’ve added some fields to our $data array: the destination directory, and the $_FILES superglobal at the “file” key, as that’s the name we set for the file in the form. We pass those values to the aptly named move_uploaded_file() function.

And that’s it! We have a slightly more complicated contact form!

## POST + email attachments
Our form will require even more work in its third iteration. After a few days of being in production, our web server’s hard drive is completely filled with images! So, now we need to change our process. We’ll upload, email, then delete the submitted photo. We don’t need to change the HTML form itself, so we’ll get right to working on the "attachment.php".

There are times for "do it yourself”, and times for using a package. This is one of the latter. Introducing PHPMailer, a super popular github package that turns this headache waiting to happen into a much simpler task. You can and should use Composer to include packages, but for the sake of simplicity, let’s go ahead and download it from github and put it in a /phpmailer/ subdirectory. This is also a pretty good time to clean up our code a little bit.

We’ve cleaned our code up to a nice object oriented format, included PHPMailer & used it to attach our uploaded file to our email, finally deleting the file after sending the email.

And that’s it! We have a reasonably complicated contact form!

## Todo:
- info on setting up local/remote email server
- More use cases?!
