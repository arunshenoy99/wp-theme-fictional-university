<?php 

class GetPets {
    function __construct() {
        global $wpdb;
        $tablename = $wpdb->prefix . 'pets';
        
        $this->args = $this->getArgs();

        $query = "SELECT * FROM $tablename ";
        $countQuery = "SELECT COUNT(*) FROM $tablename ";
        $query .= $this->createWhereText();
        $countQuery .= $this->createWhereText();
        $query .= " LIMIT 100";

        $this->count = $wpdb->get_var($wpdb->prepare($countQuery, $this->args));
        $this->pets = $wpdb->get_results($wpdb->prepare($query, $this->args));
    }

    function getArgs() {
        $temp = array(
            'favcolor' => sanitize_text_field($_GET['favcolor']),
            'species' => sanitize_text_field($_GET['species']),
            'minyear' => sanitize_text_field($_GET['minyear']),
            'maxyear' => sanitize_text_field($_GET['maxyear']),
            'minweight' => sanitize_text_field($_GET['minweight']),
            'maxweight' => sanitize_text_field($_GET['maxweight']),
            'favhobby' => sanitize_text_field($_GET['favhobby']),
            'favfood' => sanitize_text_field($_GET['favfood'])
        );
        // Filter out keys that do not have a value.
        return array_filter($temp, function ($value) {
            return $value;
        });
    }

    function createWhereText() {
        $whereQuery = '';

        if (count($this->args)) {
            $whereQuery = 'WHERE ';
        }

        $currentPosition = 0;

        foreach($this->args as $index => $item) {
            $whereQuery .= $this->specificQuery($index);
            if ($currentPosition != count($this->args) - 1) {
                $whereQuery .= ' AND ';
            }
            $currentPosition++;
        }

        return $whereQuery;
    }

    function specificQuery($index) {
        switch ($index) {
            case 'minweight':
                return "petweight >= %d";
            case 'maxweight':
                return "petweight <= %d";
            case 'minyear':
                return 'birthyear >= %d';
            case 'maxyear':
                return 'birthyear <= %d';
            default:
                return $index . " = %s";
        }
    }
}

?>