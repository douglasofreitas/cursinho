<?php 
class ExcelComponent extends Object
{

   	var $fp=null;
   	var $buffer = '';
   	var $name_file = '';
 	var $error;
    var $state="CLOSED";
    var $newRow=false;
    /*
    * @Params : $file  : file name of excel file to be created.
    * @Return : On Success Valid File Pointer to file
    *             On Failure return false
    */
    function ExcelComponent(){
        return $this->iniciando('planilha.xls');
    }
    /*
    * @Params : $file  : file name of excel file to be created.
    *                if you are using file name with directory i.e. test/myFile.xls
    *                then the directory must be existed on the system and have permissioned properly
    *                to write the file.
    * @Return : On Success Valid File Pointer to file
    *                On Failure return false
    */
    function iniciando($file, $bsc="CELLPAR"){
        if(!empty($file)){
           //$this->buffer=@fopen($file,"w+");
           $this->name_file = $file;
        }else{
            echo "Usage : New ExcelWriter('fileName')";
            return false;
        }

      	$this->buffer='';
    	$this->state="OPENED";
    	$this->buffer.=$this->GetHeader();
   		return $this->buffer;
   }
    function fechando(){
    	if($this->state!="OPENED"){
          	echo "Error : Please open the file.";
           	return false;
       	}
        if($this->newRow){
           	$this->buffer.='</tr>';
            $this->newRow=false;
        }
        $this->buffer.=$this->GetFooter();
        //fclose($this->buffer);
        $this->state="CLOSED";

        //adaptação

        if(ob_get_length())
			$this->Error('Some data has already been output, can\'t send PDF file');
		header('Content-Type: application/x-download; charset=utf-8');
		if(headers_sent())
			$this->Error('Some data has already been output, can\'t send PDF file');
		header('Content-Length: '.strlen($this->buffer));
		header('Content-Disposition: attachment; filename="'.$this->name_file.'"');
		header('Cache-Control: private, max-age=0, must-revalidate');

		header('Pragma: public');
		ini_set('zlib.output_compression','0');

		echo $this->buffer;

        return;
    }
        /* @Params : Void
        *  @return : Void
        * This function write the header of Excel file.
        */
   function GetHeader(){
        $header ='
        <html xmlns:o="urn:schemas-microsoft-com:office:office"
               xmlns:x="urn:schemas-microsoft-com:office:excel"
                xmlns="http://www.w3.org/TR/REC-html40">
                <head>
                <meta http-equiv=Content-Type content="text/html; charset=us-ascii">
                <meta name=ProgId content=Excel.Sheet>
                <!--[if gte mso 9]><xml>
                 <o:DocumentProperties>
                  <o:LastAuthor>Sriram</o:LastAuthor>
                  <o:LastSaved>2005-01-02T07:46:23Z</o:LastSaved>
                  <o:Version>10.2625</o:Version>
                 </o:DocumentProperties>
                 <o:OfficeDocumentSettings>
                  <o:DownloadComponents/>
                 </o:OfficeDocumentSettings>
                </xml><![endif]-->
                <style>
                <!--table
                    {mso-displayed-decimal-separator:"\.";
                    mso-displayed-thousand-separator:"\,";}
                @page
                    {margin:1.0in .75in 1.0in .75in;
                    mso-header-margin:.5in;
                    mso-footer-margin:.5in;}
                tr
                    {mso-height-source:auto;}
                col
                    {mso-width-source:auto;}
                br
                    {mso-data-placement:same-cell;}
                .style0
                    {mso-number-format:General;
                    text-align:general;
                    vertical-align:bottom;
                    white-space:nowrap;
                    mso-rotate:0;
                    mso-background-source:auto;
                    mso-pattern:auto;
                    color:windowtext;
                    font-size:10.0pt;
                    font-weight:400;
                    font-style:normal;
                    text-decoration:none;
                    font-family:Arial;
                    mso-generic-font-family:auto;
                    mso-font-charset:0;
                    border:none;
                    mso-protection:locked visible;
                    mso-style-name:Normal;
                    mso-style-id:0;}
                td
                    {mso-style-parent:style0;
                    padding-top:1px;
                    padding-right:1px;
                    padding-left:1px;
                    mso-ignore:padding;
                    color:windowtext;
                    font-size:10.0pt;
                    font-weight:400;
                    font-style:normal;
                    text-decoration:none;
                    font-family:Arial;
                    mso-generic-font-family:auto;
                    mso-font-charset:0;
                    mso-number-format:General;
                    text-align:general;
                    vertical-align:bottom;
                    border:none;
                    mso-background-source:auto;
                    mso-pattern:auto;
                    mso-protection:locked visible;
                    white-space:nowrap;
                    mso-rotate:0;}
                .xl24
                    {mso-style-parent:style0;
                    white-space:normal;}
                -->
                </style>
                <!--[if gte mso 9]><xml>
                 <x:ExcelWorkbook>
                  <x:ExcelWorksheets>
                   <x:ExcelWorksheet>
                    <x:Name>NOME_PLANILHA</x:Name>
                    <x:WorksheetOptions>
                     <x:Selected/>
                     <x:ProtectContents>False</x:ProtectContents>
                     <x:ProtectObjects>False</x:ProtectObjects>
                     <x:ProtectScenarios>False</x:ProtectScenarios>
                    </x:WorksheetOptions>
                   </x:ExcelWorksheet>
                  </x:ExcelWorksheets>
                  <x:WindowHeight>10005</x:WindowHeight>
                  <x:WindowWidth>10005</x:WindowWidth>
                  <x:WindowTopX>120</x:WindowTopX>
                  <x:WindowTopY>135</x:WindowTopY>
                  <x:ProtectStructure>False</x:ProtectStructure>
                  <x:ProtectWindows>False</x:ProtectWindows>
                 </x:ExcelWorkbook>
                </xml><![endif]-->
                </head>
                <body link=blue vlink=purple>
                <table x:str border=0 cellpadding=0 cellspacing=0 style="border-collapse: collapse;table-layout:fixed;">';
            return $header;
   }
    function GetFooter(){
       return '</table></body></html>';
    }
    /*
    * @Params : $line_arr: An valid array
    * @Return : Void
    */
    function writeLine($line_arr){
       if($this->state!="OPENED"){
          echo "Error : Please open the file.";
            return false;
       }
        if(!is_array($line_arr)){
           echo "Error : Argument is not valid. Supply an valid Array.";
            return false;
        }
        $this->buffer.='<tr>';
        foreach($line_arr as $col)
           $this->buffer.='<td class=xl24 width=64 >'. $col.'</td>';
        $this->buffer.="</tr>";
    }
    /*
    * @Params : Void
    * @Return : Void
    */
    function writeRow(){
      if($this->state!="OPENED"){
          echo "Error : Please open the file.";
            return false;
       }
       if($this->newRow==false){
         $this->buffer.='<tr>';
       }else{
           $this->buffer.='</tr><tr>';
            $this->newRow=true;
        }
   }
    /*
    * @Params : $value : Coloumn Value
    * @Return : Void
    */
    function writeCol($value){
       if($this->state!="OPENED"){
          echo "Error : Please open the file.";
            return false;
       }
        $this->buffer.='<td class=xl24 width=64 >'.$value.'</td>';
    }
}
?>
