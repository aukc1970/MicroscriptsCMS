# Microscripts CMS
**Version 1.1**

This lightweight, easy to use Flattfile-CMS was developed to make the **content from static wabsites dynamic** without moutch effort.

You simply discribe what input fields you need and the CMS generates a backend for you.

----------

## How to use:

1. Firstly you add the folders *backend, images and login* to the root of your HTML page.

2. Now you have to **describe what inputfields** you need in yor backend. In order to do this you open the **folder backend** and navigate to the **textfile named viewSources.txt**. Here you have to describe what inputfields you need in your backend. For this you have the following options.

Syntax | Fieldname required | Results in: 
--- | --- | ---
title | no | a title to your backend
sub_title | no | a subtitle to your backend
`|||` | no | structures new page
txt | yes | `<imput type="text">` 
ml_txt | yes | `<textarea></textarea>`
img | yes | `<imput type="file">` 
 

#### Examples:
title,Home;

sub_title,Here you find the contents from your Home-page.;

txt,maintitle,This is the maintitle on your page. 

ml_txt,introText,This is the first paragraph on your page.

img,mainBG,This is the main backgroung.

`|||` Structures new page dosent need anything more than that: `|||` 

3. For the final step you need to load the stored data into your page. All data is returned in an array called **$savedData[]**. For this you need to turn your HTML document into an .php page.  
Now your just have to embed the backend file. 
```php
<?php include_once "backend/microscriptsCMS.php" ?> 
```
Now you can add the data. For this you need to remember what names you set for the fields. 

For example: txt,**maintitle**,This is the maintitle on your page.

In this case the name from the text field is **maintitle**. 
If you wana use the text insied a H1 tag you do it like this.
```php 
<h1><?php echo $savedData["maintitle"];></h1>
```
The same it is for an **ml_txt**. ml_txt stands for multiline text. The only differance to txt fields is that it adds `<br>` tags to linebreaks.

If you wana use images you do it like that:
```html
<img src=<?php echo '"'. $savedData["mainBG"]. '"'; ?> alt="This is the main img">
```
If you wana add an image as a background you can do it like this:
```html
<div style=<?php echo '"background-image:url('.$savedData["mainBG"].');"'?></div>
``` 

----------
## Options
In order to **change login credentials** and stuff you navigate into the **backend folder** and open the **options.php**.
Here you can change the following things:

Field | Example | Description
--- | --- | ---
$correctUN | "asd"; | **Username**
$correctPW | "asdf"; | **Password**
$allowedFileTypes | "jpg,jpeg,png,gif"; | The allowed file types for uploads
$maxFileSizeInMB | 6; | The maximum allowed file size in MB 
$savesFileDirName | "storedData.txt"; | The *database* file dir and name
$fileUploadDir | "../images/"; | Upload save folder dir
$uploadedFilesDir | "images/"; | Uploaded files dir
$loginFallback | "index.php"; | Login fallback if login fails

### Now go build something awesome!
----------

## Contributors
- Leonard Schedel 

----------
## License & copyright

© Leonard Schedel, Fullstack Webdeveloper

Licensed under the [Apache License 2.0](LICENSE)
