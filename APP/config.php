<?php
//VALUE
define("CURRENT_ACCOUNT","currentAccount");
define("ACCOUNT_ROLE",array("MAHASISWA","DOSEN","ADMIN"));
define("GENDER",array("Pria","Wanita"));
define("GROUP_TYPES",array("Privat","Publik"));
define("DATETIME_REGEX","/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/");
define("API_ADDRESS","http://localhost/universityhub/APP/");
define("UPLOAD_DATABASE","../DATABASE/");
define("CODE_LENGTH",6);

//MEDIA
define("MEDIA_TYPE",array("PROFILE_PICTURE"));
define("MAX_IMAGE_SIZE",2000); //In mega bytes
define("ALLOWED_PICTURE_EXTENSION",array("jpg","jpeg","png","webp"));