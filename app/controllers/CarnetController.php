<?php
//use Dompdf\Dompdf;

Class CarnetController extends ControllerBase
{
	public function indexAction(){
		$this->pdfAction();
	}
	
	public function pdfAction()
	{
		// set the default timezone to use
		$this->view->disable();
		$html =
		'<!DOCTYPE html>
<html>
<style type="text/css">
    <!-- @page {
        margin: 10px
    }
/* latin */
@font-face {
  font-family: "AIGFuturaMed";
  font-style: normal;
  font-weight: 400;
  src: local("Give You Glory"), local("GiveYouGlory"), url(http://fonts.gstatic.com/s/giveyouglory/v6/DFEWZFgGmfseyIdGRJAxuBcAUOYGvnPYlcaEKgTjjG0.woff2) format("woff2");
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}			
    h2 {
        font-family: "AIGFuturaMed";
    }
    -->
</style>

<body>
    <h2 align="center">AIG - FRANCISCO LOZANO</h2>
    <h2 align="center">AIG - FRANCISCO LOZANO</h2>
    <h2 align="center">AIG - FRANCISCO LOZANO</h2>
    <h2 align="center">AIG - FRANCISCO LOZANO</h2>
    <h2 align="center">AIG - FRANCISCO LOZANO</h2>
    <h2 align="center">AIG - FRANCISCO LOZANO</h2>
    <h2 align="center">AIG - FRANCISCO LOZANO</h2>
    <h2 align="center">AIG - FRANCISCO LOZANO</h2>
    <h2 align="center">AIG - FRANCISCO LOZANO</h2>
    <h2 align="center">AIG - FRANCISCO LOZANO</h2>
    <h2 align="center">AIG - FRANCISCO LOZANO</h2>
</body>

</html>';
			
		$dompdf = new DOMPDF();
		$dompdf->set_option('isHtml5ParserEnabled', true);
		$dompdf->loadHtml($html);
	
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper("Letter", 'portrait'); //array(0,0,132,408), 'landscape');
	
		// Render the HTML as PDF
		$dompdf->render();
	
		// Get the generated PDF file contents
		//$pdf = $dompdf->output();
	
		// Output the generated PDF to Browser
		$dompdf->stream();
	}
}