<!DOCTYPE html>
<html>
<head>
	<title>Router Details</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-2.1.4.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://lipis.github.io/bootstrap-sweetalert/dist/sweetalert.js"></script>
    <link rel="stylesheet" href="https://lipis.github.io/bootstrap-sweetalert/dist/sweetalert.css"/>
	
	
</head>
<body>
<div class="container">
<h2>Router Details</h2>
        <div>
		<a href="#" class="btn btn-info importdata" id="import">ImportData</a>
		<a href="<?php echo base_url();?>welcome" class="btn btn-warning" id="back">Back</a>
		</div></br>
		<table id="item-list" class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
			<th>ID</th>
			<th>Sapid</th>
			<th>Hostname</th>
			<th>LoopBack</th>
			<th>MAC Address</th>
			<th>Option</th>
			</tr>
		</thead>
		<tbody>
         <?php
			$i =1;
			if(!empty($results))
			{
			  foreach ($results as $row) 
              {
				  $color = "";
				  if($row['duplicate_check'] ==1)
				  {
					  $color = "gray";
				  }else{
					  $color = "";
				  }			 
		?>
				  <tr class="gradeX" style="background-color: <?php echo $color;?>">
				  <td><?php echo $i; ?></td>
				  <td><?php echo $row['sapid']; ?></td>
				  <td><?php echo $row['hostname']; ?></td>
				  <td><?php echo $row['loopback']; ?></td>
				  <td><?php echo $row['macaddress']; ?></td>
				  
				  <td>
				  <a href="javascript:void(0)" class="btn btn-warning btn-mini routeredit" data-id="<?php echo $row['id'];?>" data-sapid="<?php echo $row['sapid'];?>" data-hostname="<?php echo $row['hostname'];?>" data-loopback="<?php echo $row['loopback'];?>" data-macaddress="<?php echo $row['macaddress'];?>">Edit</a>
				  <a href="#" name="del_item" id="<?php echo $row['id'];?>" class="btn btn-danger btn-mini remove">Remove</a></td>
				  </tr>
		<?php 
				$i++;
                } 
		    } 
		?>
        </tbody>
	    </table>
		<div>
		</div>
	
	<!-- Modal -->
<div class="modal" id="myModal">
   <div class="modal-dialog ">
       <div class="modal-content">
           <div class="modal-header">
              <h4 class="modal-title">Edit Router Details</h4>
               <button type="button" class="close" data-dismiss="modal"></button>
           </div>
          <div class="modal-body">
		  <div class="errors" id="errors"><div class=""></div></div>
         <div class="container-fluid">
			<form id="editForm">
				<div class="row">
					<div class="col-md-3">
					<label class="control-label" style="position:relative; top:7px;">Sapid:</label>
					</div>
					<div class="col-md-9">
					<input type="text" class="form-control" name="esapid" id="esapid">
					</div>
				</div>
				<div style="height:10px;"></div>
				<div class="row">
					<div class="col-md-3">
					<label class="control-label" style="position:relative; top:7px;">Hostname:</label>
					</div>
					<div class="col-md-9">
					<input type="text" class="form-control" name="ehostname" id="ehostname">
					</div>
				</div>
				<div style="height:10px;"></div>
				<div class="row">
					<div class="col-md-3">
					<label class="control-label" style="position:relative; top:7px;">LoopBack:</label>
					</div>
					<div class="col-md-9">
					<input type="text" class="form-control" name="eloopback" id="eloopback">
					</div>
				</div>
				<div style="height:10px;"></div>
				<div class="row">
					<div class="col-md-3">
					<label class="control-label" style="position:relative; top:7px;">MacAddress:</label>
					</div>
					<div class="col-md-9">
					<input type="text" class="form-control" name="emacaddress" id="emacaddress">
					</div>
				</div>
				<input type="hidden" name="eid" id="eid">
            </div>
          </div>
           <div class="modal-footer">
               <button type="submit" class="btn btn-secondary" id="cancel" data-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary" id="update_details" name="update_details">update changes</button>
          </div>
		  </form>
       </div>
  </div>
</div>


<script type="text/javascript">
    $(".remove").click(function(){
        var id = $(this).attr('id');;
    
       swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary file!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plx!",
        closeOnConfirm: false,
        closeOnCancel: false
      },
      function(isConfirm) {
        if (isConfirm) {
          $.ajax({
             url: '<?php echo base_url();?>/ajax/delete/'+id,
             type: 'DELETE',
             error: function() {
                alert('Something is wrong');
             },
             success: function(data) {
                  //$("#"+id).remove();
                  swal("Deleted!", "Your imaginary file has been deleted.", "success");
				  setTimeout(function(){
                      location.reload(); 
                  }, 1000); 
             }
          });
        } else {
          swal("Cancelled", "Your imaginary file is safe :)", "error");
        }
      });
    });
$(".routeredit").click(function () {
     
	 var id = $(this).data('id');
	 //alert(id);
	 var sapid = $(this).data('sapid');
	 var hostname = $(this).data('hostname');
	 var loopback = $(this).data('loopback');
	 var macaddress = $(this).data('macaddress');
	 
	 $('#esapid').val(sapid);
	 $('#ehostname').val(hostname);
	 $('#eloopback').val(loopback);
	 $('#emacaddress').val(macaddress);
	 $('#eid').val(id);
	 
	 
	 $("#myModal").modal("show");
});

$.validator.addMethod('IP4Checker', function(value) {
     return value.match(/^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$/);
}, 'Invalid IP address');

jQuery.validator.addMethod('MACChecker', function(value) {
    var mac = "^([0-9a-fA-F]{2}:){5}[0-9a-fA-F]{2}$";
    return value.match(mac);
}, ' Invalid MAC Address');
/*$.validator.addMethod('hostname', function(value) {
     return value.match(/^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/);
}, 'Invalid Hostname');*/
			
$(document).ready(function (){
	$('#editForm').validate({
		rules: {
			esapid: {
                required: true,
				minlength:18,
				maxlength:18
			},
			eloopback:{
				IP4Checker: true,
				required:true
			},
			emacaddress:{
				required:true,
				MACChecker: true
			},
			ehostname:{
				required:true,
				//hostname: true
				minlength:14,
				maxlength:14
			}
			
		},
		messages: {
			esapid: {
              required: "Sapid is Required"
            },
			eloopback: {
              required: "LoopBack address is Required"
            },
			emacaddress:{
				required: "Macaddress is Required"
			},
			ehostname:{
				required: "Hostname is Required"
			}
		},
		submitHandler: function (form) {
             $.ajax({
              url: '<?php echo base_url();?>Upload/updateRouterDetails',
              data: new FormData(form),
              type: 'post',
              contentType:false,
              cache: false,
              processData:false,
              dataType:'json',
              beforeSend:function(){
                $('#update_details').text('Updating...');
                $('#update_details, #cancel').attr('disabled','disabled');
              },
              complete:function(){
                $('#update_details').text('update change');
                $('#update_details,#cancel').removeAttr('disabled');                
              },
              success: function(jdata){
				  console.log(jdata);
				  if(jdata.success == true){
					  //window.load();
					  alert(jdata.message);
					  location.reload();
				  }else{
					  alert(jdata.message);
				  }
              }
            });   
		}
		
	});
});
$(".importdata").click(function (){
	$.ajax({
		url: '<?php echo base_url();?>Upload/importRouterDetails',
              type: 'post',
              dataType:'json',
              beforeSend:function(){
                ///$('#update_details').text('Updating...');
                $('#import').attr('disabled','disabled');
				
              },
              complete:function(){
                //$('#update_details').text('update change');
                $('#import').removeAttr('disabled'); 
							
              },
              success: function(jdata){
				  if(jdata.success == true){
					  alert(jdata.message);
					  location.reload();
			      }else{
					  alert(jdata.message);
				  }
				  
              }
	});
});
</script>
</body>
</html>