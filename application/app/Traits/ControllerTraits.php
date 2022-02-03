<?php

/**
 * CONTROLLER TRAITS
 * 
 * @package AA_Project
 * 
 */

namespace Api\Traits;

use Api\Hasher;

use Api\Constants;

use Api\Collection;

trait ControllerTraits {


    public function getHelper( $query, $request, $hasActiveStatus = true ) {

        if( $hasActiveStatus ) {

            if( isset( $request['inactive'] ) ) {

                $query->inactive();
    
            } else {
    
                $query->active();
    
            }

        }

        if( isset( $request['id'] ) ) {

            $query->where('id', Hasher::decode( $request['id'] ));

        }


        if( isset( $request['orderBy'] ) ) {

            $query->orderByRaw( $request['orderBy'] );

        }

        if( isset( $request['pagination'] ) ) {
            
            return $query->paginate( $request['limit'] );

        }

        return ['data' => Collection::toJson($query->get())];

    }

    public function updateOrPostHelper( $instance, $request, $class, $allowedNulls = [] ) {

        $helper = $instance;

        foreach( $request as $key => $value ) {
            
            if( in_array( $key, $allowedNulls ) ) {

                $helper[$key] = $value;

            } else {

                if( $request[$key] !== "" ) {

                    $helper[$key] = $value;

                }

            }

        }

        $helper->save();

        $ret = $class->find($helper->id);

        return $ret;

    }

    // if it is optional but need to fill.

    public function _required( $object, $request, $lists = [] ) {

        foreach( $request as $key => $value ) {

            if( in_array( $key, $lists ) ) {

                $object->rule('required', $key);

            }

        }

    }

    // multiple ids

    public function ids( $ids ) {

        try {

            $get = explode(',', $ids);

            $ret = [];

            foreach( $get as $id ) {

                $ret[] = Hasher::decode( $id );
                
            }

            return $ret;

        } catch( \Exception $e ) {

            return [];

        }

    }


    public function updateURLStatements($statements = [], $old, $new ) {

        $jsnold = addslashes(str_replace('"', '', json_encode($old)));

        $jsnnew = addslashes(str_replace('"', '', json_encode($new)));
        
        $str = null;

        if(count($statements)) {

            foreach( $statements as $q ) {

                $str .= "update ".$q['table']." set ".$q['column']." = REPLACE(".$q['column'].", '".$old."', '".$new."');";

                $str .= "update ".$q['table']." set ".$q['column']." = REPLACE(".$q['column'].", '".$jsnold."', '".$jsnnew."');";

            }

        }

        return $str;
    }



    public function massUpdate($instance, $columns = [], $keyColumn) {

        $str = '';

        foreach($columns as $key => $value) {

            foreach($value as $colkey => $colvalue) {

                if($keyColumn!=$colkey) {

                    $str .= "update " . $instance->getTable() . " set " . $colkey . '=' . $colvalue . ' where ' . $keyColumn . '=' . $value[$keyColumn] . ';';

                }

            }

        }

        return $str;

    }

    public function jsonArrayPaginate($array, $page, $limit) {

        if( !$limit ) {

            return array(
                'page' => 0,
                'total' => 0,
                'limit' => 0,
                'offset' => 0,
                'last' => 0,
                'data' => $array
            );

        }

        $thepage = !isset($page) || empty( $page ) ? 1 : $page;

        $total = count( $array );

        $totalPages = ceil( $total/$limit);

        $thepage = max($page, 1);

        $thepage = min($page, $totalPages);

        $offset = ($page - 1) * $limit;

        if( $offset < 0 ) { $offset = 0; }

        $thedata = array_slice( $array, $offset, $limit );

        return array(
            'page' => (int) $thepage,
            'total' => $total,
            'limit' => (int) $limit,
            'offset' => $offset,
            'last' => $totalPages,
            'data' => $thedata
        );

    }


    public function jsonArray( $json ) {

        if( $json ) {

            $ret = json_decode( $json );

            if( is_array( $ret )) {

                return $ret;

            }

        }

        return [];

    }


    public function executeEval( $code ) {

        try {

            ob_start();

            eval( $code );

            $output = ob_get_clean();

            return $output;

        } catch(\Exception $e) {

            return $code;

        }

    }


    public function specificationIterate( $product ) {

        if( !$product['spechandleroutput'] ) {

            return [];

        }

        $handler = $product['spechandleroutput']['spc'];

        $combospecs = $product['specification'];

        $productspecs = $product['product']['specification'];

        $fields = [];

        foreach( $combospecs as $cs ):
            $fields = array_merge( $fields, $cs['fields'] );
        endforeach;

        foreach( $productspecs as $ps ):
            $fields = array_merge( $fields, $ps['fields']);
        endforeach;

        $specificationdata = [];

        $specificationdata[] = array(
            'label' => 'ITEM NUMBER',
            'value' => $product['product_method_combination_name']
        );

        $specificationdata[] = array(
            'label' => 'Item Description',
            'value' => $product['product']['product_description']
        );

        foreach( $handler as $hl ):
            $pattern = '/\{[^{}]+\}/im';
            preg_match_all($pattern, $hl['filter'], $matches);
            $datamatch = [];
            if( count( $matches ) ) {
                $datamatch = $matches[0];
            }

            foreach($datamatch as $rm):
                $nobracket = str_replace(array('{', '}'), '', $rm);
                $arrkey = array_search($nobracket, array_column($fields, 'key'));
                if( is_int( $arrkey ) ) {
                    $hl['filter'] = str_replace(array($rm), $fields[$arrkey]['value'], $hl['filter']);
                }
            endforeach;

            if( isset($hl['isexec']) && $hl['isexec'] ) {
                $hl['filter'] = $this->executeEval( $hl['filter'] );
            }

            $specificationdata[] = array(
                'label' => $hl['label'],
                'value' => $hl['filter']
            );
        endforeach;

        return $specificationdata;

    }

}