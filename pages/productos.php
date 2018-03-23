<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
<?php

?>

<div class="row">
  <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                Productos
            </header>
            <section class="panel">
                        <div class="panel-body">
                         <div class="table-responsive">
                                <table  class="display table table-bordered table-striped" id="dynamic-table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Nombre</th>
                                            <th>Serial</th>
                                            <th>Stock</th>
                                            <th>Precio</th>
                                            <th><i class="fa fa-eye"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $result as $row => $field ) : ?> <!-- Mulai loop -->
                                        <tr class="text-besar">
                                            <?$Duration = round(abs($field['start'] - $field['stop']) / 60,2)?>
                                            <td><?php echo $field['id']; ?></td>
                                            <td><?php echo $field['start']; ?></td>
                                            <td><?php echo $field['stop']; ?></td>
                                            <td><?php echo $field['duration'] . " Minutes";?></td>
                                            <td>
                                                <a class="btn btn-success btn-xs" target="_blank" href="pages/pdf.php?id=<?php echo $field['id']; ?>" title="Ver reporte">
                                                    <i class="fa fa-eye"></i>
                                                </a>
</td>
                                        </tr>
                                        <?php endforeach; ?> <!-- Selesai loop -->                                  
                                    </tbody>
</table>
                        </div>
            </section>
        </section>
    </div>
</div>