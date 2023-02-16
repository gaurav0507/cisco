<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title>Live editable table with jQuery AJAX in CodeIgniter</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://lipis.github.io/bootstrap-sweetalert/dist/sweetalert.js"></script>
    <link rel="stylesheet" href="https://lipis.github.io/bootstrap-sweetalert/dist/sweetalert.css" />

   <style type="text/css">
    .txtedit{
      display: none;
      width: 98%;
    }
   </style>
</head>
<body>

    <table width='100%' border='1' style='border-collapse: collapse;'>
        <thead>
          <tr>
            <th width='15%'>Sapid</th>
            <th width='15%'>Hostname</th>
			<th width='15%'>Loopback</th>
			<th width='15%'>Macaddress</th>
			<th width='15%'>option</th>
          </tr>
        </thead>
        <?php 
        // User List
        foreach($results as $user){
            $id = $user['id'];
            $sapid = $user['sapid'];
            $hostname = $user['hostname'];
			$loopback = $user['loopback'];
			$macaddress = $user['macaddress'];

            echo "<tr>";
            echo "<td>
                <span class='edit' >".$sapid."</span>
                <input type='text' class='txtedit' data-id='".$id."' data-field='sapid' id='sapidtxt_".$id."' value='".$sapid."' >
            </td>";
            echo "<td>
                <span class='edit' >".$hostname."</span>
                <input type='text' class='txtedit' data-id='".$id."' data-field='hostname' id='hostnametxt_".$id."' value='".$hostname."' >
            </td>";
			 echo "<td>
                <span class='edit' >".$loopback."</span>
                <input type='text' class='txtedit' data-id='".$id."' data-field='loopback' id='loopbacktxt_".$id."' value='".$loopback."' >
            </td>";
			 echo "<td>
                <span class='edit' >".$macaddress."</span>
                <input type='text' class='txtedit' data-id='".$id."' data-field='macaddress' id='macaddresstxt_".$id."' value='".$macaddress."' >
            </td>";
			echo "<td><a href='#' name='del_item' id=".$id." class='btn btn-danger btn-small remove'>Remove</a></td></td>";
			
			
            echo "</tr>";
        }
        ?>

    </table>

    <script type="text/javascript">
    $(document).ready(function(){

        $('.edit').click(function(){
			$('.txtedit').hide();
			$(this).next('.txtedit').show().focus();
			$(this).hide();
        });
        $('.txtedit').focusout(function(){
            
            var edit_id = $(this).data('id');
            var fieldname = $(this).data('field');
            var value = $(this).val();
            $(this).hide();
            $(this).prev('.edit').show();
            $(this).prev('.edit').text(value);
            $.ajax({
              url: '<?= base_url()?>Ajax/updateRouterDetails',
              type: 'post',
              data: { field:fieldname, value:value, id:edit_id },
              success:function(response){
                 console.log(response);
               }
            });
        });
    });
    </script>
   
</body>
</html>







