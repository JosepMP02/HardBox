function getID {
    param (
        $nombre
    )
    $nombre = $nombre.ToString()
    $id = ''

    # No tengais en cuenta esta cutrez de codigo... 
    # he probado a hacerlo recorriendo arrays, 
    # pero LITERALMENTE powershell esta rotisimo
    # es que paso ya de esto, no voy a hacer ni un switch-case
    # me sudan los coj****.

    if($nombre -like "*MRROBOT*"){
        $id = 1
    }elseif($nombre -like "*ASIX*"){
        $id = 2
    }elseif($nombre -like "*maquinaY*"){
        $id = 3
    }elseif($nombre -like "*maquinaZ*"){
        $id = 4
    }elseif($nombre -like "*maquinaH*"){
        $id = 5
    }elseif($nombre -like "*maquinaA*"){
        $id = 6
    }elseif($nombre -like "*maquinaN*"){
        $id = 7
    }else{
        $id = -1
    }
    
    return $id
}


$maquina = Get-VM | Where-Object {$_.State -eq 'Running'}

$VM1 = ''
$VM2 = ''
$VM3 = ''
$VM4 = ''
$maquinaFormat = ''

for($i = 0; $i -lt $maquina.length; $i++){
    if($maquina[$i] -notlike "*Ubuntu*"){
        $maquinaFormat = ($maquina[$i] | select Name | Format-Table -HideTableHeaders | Out-String).Replace("`n","")
        if($i -eq 0){ $VM1 = getID($maquinaFormat) }
        if($i -eq 1){ $VM2 = getID($maquinaFormat) }
        if($i -eq 2){ $VM3 = getID($maquinaFormat) }
        if($i -eq 3){ $VM4 = getID($maquinaFormat) }
    }
}

$url = "https://hardbox.ga/php/updateMachines.php?nombre1=$VM1&nombre2=$VM2&nombre3=$VM3&nombre4=$VM4&code=Change-Makina12354"
$url = $url.ToString()
echo $url

#Guardar LOG en un fichero
$fecha = Get-Date -UFormat "%m/%d/%Y %R"
echo $fecha " | URL --> " $url.Replace("`n","") "------`n" | Out-File -FilePath "D:\log\updateWeb_log.txt" -Append

#Actualizar la web con esta peticion
Invoke-WebRequest -URI $url
