$maquinasataque=Get-ChildItem -Path D:\MV_EX\ATAQUE\
$maquinasdefensa=Get-ChildItem -Path D:\MV_EX\DEFENSA
Write-Host Get-Random -Maximum $maquinasatque.Count
$nombres=Get-VM | SELECT NAME

$MV1=""
$MV2=""
$MV3=""
$MV4=""
while($MV1 -eq $MV2){
    $MV1 = $maquinasatque[(Get-Random -Maximum ($maquinasatque.Count))]
    $MV2 = $maquinasatque[(Get-Random -Maximum ($maquinasatque.Count))]
}

while($MV3 -eq $MV4){
    $MV3 = $maquinasatque[(Get-Random -Maximum ($maquinasatque.Count))]
    $MV4 = $maquinasatque[(Get-Random -Maximum ($maquinasatque.Count))]
}

foreach($maquina in $nombres){
    if($maquina -ne "Ubuntu"){
        Remove-VM -Name $maquina    
    }
}

Import-VM -VhdDestinationPath D:\MV -VirtualMachinePath D:\MV_EX\ATAQUE\$MV1
Import-VM -VhdDestinationPath D:\MV -VirtualMachinePath D:\MV_EX\ATAQUE\$MV2
Import-VM -VhdDestinationPath D:\MV -VirtualMachinePath D:\MV_EX\DEFENSA\$MV3
Import-VM -VhdDestinationPath D:\MV -VirtualMachinePath D:\MV_EX\DEFENSA\$MV4