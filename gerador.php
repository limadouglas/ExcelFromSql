<?php
    require_once 'Classes/PHPExcel.php';

    $uploaddir = 'uploads/';
    $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

    $arquivoScript = "script/script.sql";
    $nomeTabela = "tbl";
    $insert = "";
    $erro = "";

    $bloqueado = true;      // var de segurança, arquivo como .php ou .html e etc... não podem ser anexados.


    // verificando extenção do arquivo.
    switch(pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION)) {
        case 'xls' :   $bloqueado = false; break;
        case 'xlsx':   $bloqueado = false; break;
        default    :   $bloqueado = true;  
                       $erro = "Tipo de arquivo não permitido";
                       break;
    }
    
    // movendo arquivo para pasta uploads.
    if (!(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) && !$bloqueado) {
        $erro = "erro ao tentar manipular arquivo, por favor tente novamente!";
        //print_r ($_FILES['error']);
        $bloqueado = true;
    }

    // executando conversão.
    if(!$bloqueado) {                                                // verificando se o arquivo não esta bloqueado.
        $nomeTabela = $_POST['tabela'];                             // recebendo nome da tabela por metodo post. 
        $trtEspaco = isset($_POST['espaco'])? true: false;          // recebendo tratamento de espaço
        $inputFileName = $uploadfile;                               // endereço do arquivo a ser convertido.


        $objPhpExcel = PHPExcel_IOFactory::load($inputFileName);    // instanciando arquivo com a lib PHPExcel.

        // descobrindo total de colunas
        $colunas = $objPhpExcel->setActiveSheetIndex(0)->getHighestColumn();
        $total_colunas = PHPExcel_Cell::columnIndexFromString($colunas);

        // decobrindo total de linhas
        $total_linhas = $objPhpExcel->setActiveSheetIndex(0)->getHighestRow();
        

        $arquivoSQL = fopen($arquivoScript, "w");

        for($coluna=0; $coluna < $total_colunas; $coluna++) {        
            $indiceColuna[0] = addslashes(utf8_decode($objPhpExcel->getActiveSheet()->getCellByColumnAndRow($coluna, 1)->getValue()));
        }

        // convertendo array de coluna string.
        $col = implode(", ", $indiceColuna);

        for($linha=2; $linha <= $total_linhas; $linha++, $valores = array()) {

            for($coluna=0; $coluna < $total_colunas; $coluna++) {
                if($trtEspaco){
                    $valores[$coluna] = trim(addslashes(utf8_decode($objPhpExcel->getActiveSheet()->getCellByColumnAndRow($coluna, $linha)->getValue())));  // pegando valor convertendo para utf8 e removendo espaços no inicio e no fim da string.
                }else{
                    $valores[$coluna] = addslashes(utf8_decode($objPhpExcel->getActiveSheet()->getCellByColumnAndRow($coluna, $linha)->getValue()));
                 }
            }

            // convertendo array de linha em string.
            $lin = implode("', '", $valores);
            
            // gravando insert no arquivo script.sql => insert into tabela (coluna1, coluna2, coluna3) values (valor1, valor2, valor3);
            fwrite($arquivoSQL, "insert into {$nomeTabela} ({$col}) values ('{$lin}');\r\n");
        }
 
        fclose($arquivoSQL);
        unlink($uploadfile);

       echo  '<a href=' . $arquivoScript . ' download>baixar</a>';

    } else {
        echo $erro;
    }




?>
