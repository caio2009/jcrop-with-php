<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">

	<title>JCrop Test</title>

	<link rel="stylesheet" href="https://unpkg.com/jcrop/dist/jcrop.css">
	<script src="https://unpkg.com/jcrop"></script>

	<style>
		body {font-family: 'Helvetica'}
	</style>
</head>

<body>

	<div>
		<label for="input_image">Select image:</label>
		<input type="file" id="inputImage" name="input_image" accept="image/*">
		
		<input type="hidden" id="x" name="x">
		<input type="hidden" id="y" name="y">
		<input type="hidden" id="width" name="width">
		<input type="hidden" id="height" name="height">

		<br><br>

		<button type="button" onclick="cropImage()">Crop Image</button>

		<br><br>

		<div style="display: flex; flex-direction: row">
			<div style="margin: 5px; display: flex; flex-direction: column">
				<small>Image to crop:</small>
				<img src="" id="imageToCrop">
			</div>
			<div style="margin: 5px; display: flex; flex-direction: column">
				<small>Cropped Image:</small>
				<img src="" id="croppedImage">
			</div>
		</div>

		<!-- <br><br>
		<p id="measures">
			top, left, width, height
		</p> -->
	</div>

	<script>
		// Global Variables
		let jcrop = null
		let imageToCrop = null
		
		if (window.File && window.FileReader && window.FileList && window.Blob) {
		  // Great success! All the File APIs are supported.
		  document.getElementById("inputImage").addEventListener("change", handleImageSelect, false)

		} else {
		  alert('The File APIs are not fully supported in this browser.');
		}

		function handleImageSelect(event) {
			imageToCrop = event.target.files[0]
			let reader = new FileReader()

			reader.onloadend = () => {
				document.getElementById("imageToCrop").src = reader.result;

				// Starting Jcrop
				jcrop = Jcrop.attach("imageToCrop")

				jcrop.listen("crop.update", (widget, e) => {
					let p = document.querySelector("#measures")

					// Get crop widget from jcrop
					let cropWidget = jcrop.active

					document.querySelector("#x").value = cropWidget.pos.x
					document.querySelector("#y").value = cropWidget.pos.y
					document.querySelector("#width").value = cropWidget.pos.w
					document.querySelector("#height").value = cropWidget.pos.h

					// Showing crop widget attributes
					// let text = ""
					// text += "top: " + cropWidget.pos.y + "\n"
					// text += "left: " + cropWidget.pos.x + "\n"
					// text += "width: " + cropWidget.pos.w + "\n"
					// text += "height: " + cropWidget.pos.h + "\n"

					// p.innerText = text
				})
			}

			if (imageToCrop) {
				reader.readAsDataURL(imageToCrop)
			}
			else {
				console.log("Problem while reading file...")
			}
		}

		function cropImage() {
			let formData = new FormData()
			formData.append("image_to_crop", imageToCrop)
			formData.append("x", document.querySelector("#x").value)
			formData.append("y", document.querySelector("#y").value)
			formData.append("width", document.querySelector("#width").value)
			formData.append("height", document.querySelector("#height").value)

			const options = {
				method: "POST",
				body: formData
			}

			fetch("cropImage.php", options)
			.then(response => response.blob())
			.then(blob => {
				console.log(blob)
				document.querySelector("#croppedImage").src = URL.createObjectURL(blob)
			})

			// fetch("cropImage.php", options).then(response => response.text()).then(text => console.log(text))
		}

	</script>


</body>

</html>