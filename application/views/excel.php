<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
	<meta name="description" content="The tiny framework with powerful features">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="<?php echo base_url();?>js/jquery.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery.validate.min.js"></script>
	<script src="<?php echo base_url();?>js/additional-methods.min.js"></script>
	<style>
	  .container {
		max-width: 500px;
	  }
	</style>
</head>
<body>
<div class="container mt-5">
	<div class="card">
		<div class="card-header text-center">
		<strong>Upload CSV File</strong>
		</div>
		<div class="card-body">
		<div class="mt-2">
		<div class="info-box-content __web-inspector-hide-shortcut__">
		<a href="<?php echo base_url();?>download/sample_format.xlsx">Download Format</a>
        </div>
		<div class="alert">
		
		</div>
		</div>	
			<form action="<?php echo base_url();?>Upload/import" method="post" id="importdata" name="importdata" enctype="multipart/form-data">
				<div class="form-group mb-3">
					<div class="mb-3">
						<input type="file" name="filedata" id="filedata" class="form-control">
					</div>					   
				</div>
				<div class="d-grid">
					<input type="submit" name="submit" value="Upload" class="btn btn-dark" />
				</div>
			</form>
		</div>
	</div>
</div>


<script type="text/javascript">
$(function() {
  $.validator.addMethod("extension", function(value, element, param) {
    param = typeof param === "string" ? param.replace(/,/g, "|") : "xlsx";
    return this.optional(element) || value.match(new RegExp("\\.(" + param + ")$", "i"));
  }, $.validator.format("Please select excel sheet with extension .xlsx"));
  $.validator.addMethod('filesize', function(value, element, param) {
    param = param * 1000000; // param in bytes
    return this.optional(element) || (element.files[0].size <= param)
  }, 'File size exceeds {0} MB');
  $('#importdata').validate({
    rules: {
      filedata: {
        required: true,
        extension: "xlsx",
        filesize: 2
      }
    },
    messages: {
      filedata: {
        required: 'Please Select file'
      }
    },
    submitHandler: function(form) {
        form.submit();
		//alert('Ya poar coming');
    }
  });
});

</script>
</body>
</html>