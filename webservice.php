<?php
/***************************IMPORTS***************************/
require_once 'bd.php';
require_once 'aux.php';
/***************************IMPORTS***************************/

/***************************INTERPRETAÇÂO***************************/
header("Content-type: text/xml");

switch($_SERVER[REQUEST_METHOD]){
    case 'GET':
        BuscaCompras($_GET[id]);
        break;
    case 'POST':
        InsereCompra($_POST[id], $_POST[detalhes], $_POST[preco]);
        break;
    case 'PUT':
        parse_str(file_get_contents("php://input"),$post_vars);
        AtualizaCompra($post_vars[id]);
        break;
}
/***************************INTERPRETAÇÂO***************************/

/***************************FUNÇÕES***************************/
function BuscaCompras($id){
    
    if(!isset($id)){
        $xml = xml('ERRO', 'Falha na Consulta', 'ID em branco');
        return $xml;
    }
    
    $stmt = $GLOBALS['db']->prepare(
    'SELECT detalhes_venda.id_venda, detalhes, preco, pago
    FROM detalhes_venda INNER JOIN vendas
    ON vendas.id_venda = detalhes_venda.id_venda
    AND id_usuario = :id');
    $status = $stmt->execute(array(
        'id' => $id
    ));
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($status){
        if(!empty($resultado)){
            $xml = xml('OK', 'Consulta feita com sucesso', $resultado);
        } else {
            $xml = xml('OK', 'Consulta não retornou nenhum valor', $resultado);
        }
    } else {
        $xml = xml('ERRO', 'Falha na Consulta', $stmt->errorInfo()); 
    }
    
    echo $xml;
    
}

function InsereCompra($id_usuario, $detalhes, $preco){
    
    if(!isset($id_usuario) || !isset($detalhes) || !isset($preco)){
        $xml = xml('ERRO', 'Falha na Inserção', 'Um dos parametros em branco');
        return $xml;
    }

    $stmt = $GLOBALS['db']->prepare(
    'INSERT INTO vendas (id_usuario, pago)
    VALUES (:id_usuario, :pago)');
    $status = $stmt->execute(array(
        'id_usuario' => $id_usuario,
        'pago' => 0
    ));
    
    if (!$status){
        $xml = xml('ERRO', 'Falha na Inserção', $stmt->errorInfo());
        return $xml;
    }
    
    $id = $GLOBALS['db']->lastInsertId();
    
    $stmt = $GLOBALS['db']->prepare(
    'INSERT INTO detalhes_venda (detalhes, id_venda, preco)
    VALUES (:detalhes, :id_venda, :preco)');
    $status = $stmt->execute(array(
        'detalhes' => $detalhes,
        'id_venda' => $id,
        'preco' => $preco
    ));
    
    if ($status){
        $xml = xml('OK', 'Inserção feita com sucesso', $id);
    } else {
        $xml = xml('ERRO', 'Falha na Inserção', $stmt->errorInfo()); 
    }
    
    echo $xml;
    
}

function AtualizaCompra($id){
    
    if(!isset($id)){
        $xml = xml('ERRO', 'Falha na Atualização', 'ID em branco');
        return $xml;
    }
    
    $stmt = $GLOBALS['db']->prepare(
    'UPDATE vendas
    SET pago = 1
    WHERE id_venda = :id_venda');
    $status = $stmt->execute(array(
        'id_venda' => $id
    ));
    
    if ($status){
        $xml = xml('OK', 'Atualização feita com sucesso', 'Compra com id '.$id.' paga');
    } else {
        $xml = xml('ERRO', 'Falha na Atualização', $stmt->errorInfo()); 
    }
    
    echo $xml;
    
}
/***************************FUNÇÕES***************************/