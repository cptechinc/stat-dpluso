<?php 
    if (checkformatterifexists($user->loginid, 'ii-purchase-history', false)) {
        $formatterjson = json_decode(getformatter($user->loginid, 'ii-purchase-history', false), true);
    } else {
        $default = $config->paths->content."item-information/screen-formatters/default/ii-purchase-history.json";
        $formatterjson = json_decode(file_get_contents($default), true);
    }

    $detailcolumns = array_keys($formatterjson['detail']['columns']);
    $lotserialcolumns = array_keys($formatterjson['lotserial']['columns']);
    $fieldsjson = json_decode(file_get_contents($config->companyfiles."json/iiphfmattbl.json"), true);

    $table = array(
        'maxcolumns' => $formatterjson['cols'],
        'detail' => array('maxrows' => $formatterjson['detail']['rows'], 'rows' => array()),
        'lotserial' => array('maxrows' => $formatterjson['lotserial']['rows'], 'rows' => array())
      );


    for ($i = 1; $i < $formatterjson['detail']['rows'] + 1; $i++) {
        $table['detail']['rows'][$i] = array('columns' => array());
        foreach($detailcolumns as $column) {
            if ($formatterjson['detail']['columns'][$column]['line'] == $i) {
                $col = array(
                    'id' => $column, 
                    'label' => $formatterjson['detail']['columns'][$column]['label'],
                    'column' => $formatterjson['detail']['columns'][$column]['column'],
                    'col-length' => $formatterjson['detail']['columns'][$column]['col-length'], 
                    'before-decimal' => $formatterjson['detail']['columns'][$column]['before-decimal'],
                    'after-decimal' => $formatterjson['detail']['columns'][$column]['after-decimal'],
                    'date-format' => $formatterjson['detail']['columns'][$column]['date-format']
                );
                $table['detail']['rows'][$i]['columns'][$formatterjson['detail']['columns'][$column]['column']] = $col;
            }
        }
    }

    for ($i = 1; $i < $formatterjson['lotserial']['rows'] + 1; $i++) {
        $table['lotserial']['rows'][$i] = array('columns' => array());
        foreach($lotserialcolumns as $column) {
            if ($formatterjson['lotserial']['columns'][$column]['line'] == $i) {
                $col = array(
                    'id' => $column,
                    'label' => $formatterjson['lotserial']['columns'][$column]['label'],
                    'column' => $formatterjson['lotserial']['columns'][$column]['column'],
                    'col-length' => $formatterjson['lotserial']['columns'][$column]['col-length'],
                    'before-decimal' => $formatterjson['lotserial']['columns'][$column]['before-decimal'],
                    'after-decimal' => $formatterjson['lotserial']['columns'][$column]['after-decimal'],
                    'date-format' => $formatterjson['lotserial']['columns'][$column]['date-format']
                );
                $table['lotserial']['rows'][$i]['columns'][$formatterjson['lotserial']['columns'][$column]['column']] = $col;
            }
        }
    }
    return $table;
?>
