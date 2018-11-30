<?php
$QueryTabla =	mysqli_query($link,"SELECT * FROM baja");
$RowTabla	=	mysqli_fetch_array($QueryTabla);
?>

<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                Inventario OSS
            </header>
            <section class="panel">
                        <div class="panel-body"> 
                            <hr/>
                            <div class="table-responsive">
                                <table  class="display table table-bordered table-striped" id="dynamic-table">
                                    <thead>
                                        <tr>
                                            <th>Referencia</th>
                                            <th>Motivo de baja</th>
                                            <th>Descripción del problema</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $QueryTabla as $RowTabla => $field ) : ?> <!-- Mulai loop -->
                                        <tr class="text-besar">
                                            <td><?php echo $field['ref']; ?></td>
                                            <td><?php echo $field['motivo']; ?></td>
                                            <td><?php echo $field['des']; ?></td>                                            
                                        </tr>
                                        <?php endforeach; ?> <!-- Selesai loop -->                                  
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
        </div>
    </section>
</div>