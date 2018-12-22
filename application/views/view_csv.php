<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
            <td width = "10%">PROVIDER</td>
            <td width = "10%">FRECUENCIA</td>
            <td width = "10%">CANTIDAD</td>
            <td width = "10%">TIEMPO</td>
            <td width = "10%">FOTOS</td>
            <td width = "10%">VALOR</td>
            <td width = "10%">ESPECIAL</td>
            <td width = "10%">AHORROS</td>
            <td width = "10%">DESCUENTO</td>
    </tr>

            <?php foreach($csvData as $field){?>
                <tr>
                    <td><?php echo $field['provider']?></td>
                    <td><?php echo $field['frecuencia']?></td>
                    <td><?php echo $field['cantidad']?></td>
                    <td><?php echo $field['tiempo']?></td>
                    <td><?php echo $field['fotos_suscripcion']?></td>
                    <td><?php echo $field['valor']?></td>
                    <td><?php echo $field['especial']?></td>
                    <td><?php echo $field['ahorro']?></td>
                    <td><?php echo $field['descuento']?></td>
                </tr>
            <?php }?>
</table>
