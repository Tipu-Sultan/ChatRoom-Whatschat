<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <title>Demo</title>
</head>
<body>
   <form id="messagef" method="post">
      <input type="file" id="file-inputs">
      <br>
      <input type="submit" name="submit" value="submit" />
   </form>
<script type="text/javascript">
   var myForm = document.getElementById("messagef");
    var inpFile = document.getElementById("file-inputs");
     myForm.addEventListener("submit", e => {
      e.preventDefault();

      var endpoint = "chat_mailer.php";
      var formData = new FormData();

      console.log(inpFile.files);

      formData.append("inpFile",inpFile.files[0]);
     });
</script>
</body>
</html>