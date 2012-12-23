<?php
namespace GPX;

class Importer {

    private static $_gps_counter;

    public static function import($filename) {
        $doc = new \DOMDocument();
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;

        static::$_gps_counter = 1;

        if ($doc->load($filename)) {
            $gpx_data = array();

            static::$_gps_counter = 1;
            $gpx_data['full'] = static::process_gpx($doc->firstChild);

            static::$_gps_counter = 1;
            $gpx_data['minimum'] = static::minimum($gpx_data['full']);

            return $gpx_data;
        } else {
            return false;
        }
    }
    
    private static function process_gpx($node) {

        $gpx = array();

        foreach ($node->childNodes as $child) {
            switch ($child->nodeName) {
                case 'metadata':
                    $gpx['metadata'] = static::process_metadata($child);
                    break;
                case 'wpt':
                    $gpx['wpt'][] = static::process_wpt($child, true);
                    break;
                case 'rte':
                    $gpx['rte'][] = static::process_rte($child);
                    break;
                case 'trk':
                    $gpx['trk'][] = static::process_trk($child);
                    break;
               case 'extensions':
                   // $gpx['extensions'] = static::process_extensions($child);
                   break;
            }
        }

        return $gpx;
    }

    private static function process_metadata($node) {
        
        $metadata = array();

        foreach ($node->childNodes as $child) {
            switch ($child->nodeName) {
                case 'name':
                    $metadata['name'] = $child->nodeValue;
                    break;
                case 'desc':
                    $metadata['desc'] = $child->nodeValue;
                    break;
                case 'author':
                    $metadata['author'] = static::process_person($child);
                    break;
                case 'copyright':
                    $metadata['copyright'] = static::process_copyright($child);
                    break;
                case 'link':
                    $metadata['link'][] = static::process_link($child);
                    break;
                case 'time':
                    $metadata['timestamp'] = static::process_time($child);
                    break;
                case 'keywords':
                    $metadata['keywords'] = $child->nodeValue;
                    break;
                case 'bounds':
                    $metadata['bounds'] = static::process_bounds($child);
                    break;
                case 'extensions':
                    // $metadata['extensions'] = static::process_extensions($child);
                    break;
            }
        }

        return $metadata;
    }

    private static function process_wpt($node, $enable_id = false) {

        $waypoint = array();

        if ($enable_id) {
            $waypoint['gps_data_id'] = static::$_gps_counter++;
        }

        foreach ($node->attributes as $name => $value) {
            switch ($name) {
                case 'lat':
                    $waypoint['latitude'] = $value->value;
                    break;
                case 'lon':
                    $waypoint['longitude'] = $value->value;
                    break;
            }
        }

        foreach ($node->childNodes as $child) {
            switch ($child->nodeName) {
                case 'ele':
                    $waypoint['elevation'] = $child->nodeValue;
                    break;
                case 'time':
                    $waypoint['timestamp'] = static::process_time($child);
                    break;
                case 'magvar':
                    $waypoint['magvar'] = $child->nodeValue;
                    break;
                case 'geoidheight':
                    $waypoint['geoidheight'] = $child->nodeValue;
                    break;
                case 'name':
                    $waypoint['name'] = $child->nodeValue;
                    break;
                case 'cmt':
                    $waypoint['cmt'] = $child->nodeValue;
                    break;
                case 'desc':
                    $waypoint['desc'] = $child->nodeValue;
                    break;
                case 'src':
                    $waypoint['src'] = $child->nodeValue;
                    break;
                case 'link':
                    $waypoint['link'][] = static::process_link($child);
                    break;
                case 'sym':
                    $waypoint['sym'] = $child->nodeValue;
                    break;
                case 'type':
                    $waypoint['type'] = $child->nodeValue;
                    break;
                case 'fix':
                    $waypoint['fix'] = $child->nodeValue;
                    break;
                case 'sat':
                    $waypoint['sat'] = $child->nodeValue;
                    break;
                case 'hdop':
                    $waypoint['hdop'] = $child->nodeValue;
                    break;
                case 'vdop':
                    $waypoint['vdop'] = $child->nodeValue;
                    break;
                case 'pdop':
                    $waypoint['pdop'] = $child->nodeValue;
                    break;
                case 'ageofdgpsdata':
                    $waypoint['ageofdgpsdata'] = $child->nodeValue;
                    break;
                case 'dgpsid':
                    $waypoint['dgpsid'] = $child->nodeValue;
                    break;
                case 'extensions':
                    // $waypoint['extensions'] = process_extensions($child);
                    break;
            }
        }
        
        return $waypoint;
    }

    private static function process_rte($node) {

        $rte = array();
        $rte['gps_data_id'] = static::$_gps_counter++;

        foreach ($node->childNodes as $child) {
            switch ($child->nodeName) {
                case 'name':
                    $rte['name'] = $child->nodeValue;
                    break;
                case 'cmt':
                    $rte['cmt'] = $child->nodeValue;
                    break;
                case 'desc':
                    $rte['desc'] = $child->nodeValue;
                    break;
                case 'src':
                    $rte['src'] = $child->nodeValue;
                    break;
                case 'link':
                    $rte['link'][] = static::process_link($child);
                    break;
                case 'number':
                    $rte['number'] = $child->nodeValue;
                    break;
                case 'type':
                    $rte['type'] = $child->nodeValue;
                    break;
                case 'extensions':
                    // $rte['extensions'] = static::process_extensions($child);
                    break;
                case 'rtept':
                    $rte['rtept'][] = static::process_wpt($child);
                    break;
            }
        }

        return $rte;
    }

    private static function process_trk($node) {

        $trk = array();
        $trk['gps_data_id'] = static::$_gps_counter++;

        foreach ($node->childNodes as $child) {
            switch ($child->nodeName) {
                case 'name':
                    $trk['name'] = $child->nodeValue;
                    break;
                case 'cmt':
                   $trk['cmt'] = $child->nodeValue;
                   break;
                case 'desc':
                    $trk['desc'] = $child->nodeValue;
                    break;
                case 'src':
                    $trk['src'] = $child->nodeValue;
                    break;
                case 'link':
                    $trk['link'][] = static::process_link($child);
                    break;
                case 'number':
                    $trk['number'] = $child->nodeValue;
                   break;
                case 'type':
                    $trk['type'] = $child->nodeValue;
                    break;
                case 'extensions':
                    // $trk['extensions'] = static::process_extensions($child);
                    break;
                case 'trkseg':
                    $trk['trkseg'][] = static::process_trkseg($child);
                    break;
            }
        }
        
        return $trk;
    }

    // function process_extensions($node) {
    //     return FALSE;
    // }

    private static function process_person($node) {
        
        $person = array();

        foreach ($node->childNodes as $child) {
            switch ($child->nodeName) {
                case 'name':
                    $person['name'] = $child->nodeValue;
                    break;
                case 'email':
                    $person['email'] = static::process_email($child);
                    break;
                case 'link':
                    $person['link'][] = static::process_link($child);
                    break;
            }
        }

        return $person;
    }

    private static function process_copyright($node) {

        $copyright = array();

        $attr = $node->attributes;
        $copyright['author'] = $attr->getNamedItem('author');

        foreach ($node->childNodes as $child) {
            switch ($child->nodeName) {
                case 'year':
                    $copyright['year'] = $child->nodeValue;
                    break;
                case 'license':
                    $copyright['license'] = $child->nodeValue;
                    break;
            }
        }

        return $copyright;
    }

    private static function process_link($node) {

        $link = array();

        $attr = $node->attributes;
        $link['href'] = $attr->getNamedItem('href');

        foreach ($node->childNodes as $child) {
            switch ($child->nodeName) {
                case 'text':
                    $link['text'] = $child->nodeValue;
                    break;
                case 'type':
                    $link['type'] = $child->nodeValue;
                    break;
            }
        }

        return $link;
    }

    private static function process_bounds($node) {

        $bounds = array();

        foreach ($node->attributes as $name => $value) {
            switch ($name) {
                case 'minlat':
                    $bounds['minlat'] = $value->value;
                    break;
                case 'minlon':
                    $bounds['minlon'] = $value->value;
                    break;
                case 'maxlat':
                    $bounds['maxlat'] = $value->value;
                    break;
                case 'maxlon':
                    $bounds['maxlon'] = $value->value;
                    break;
            }
        }

        return $bounds;
    }

    private static function process_email($node) {
        $attr = $node->attributes;

        return $attr->getNamedItem('id') . '@' . $attr->getNamedItem('domain');
    }

    private static function process_trkseg($node) {
        
        $trkseg = array();
        
        foreach ($node->childNodes as $child) {
            switch ($child->nodeName) {
                case 'trkpt':
                    $trkseg['waypoints'][] = static::process_wpt($child);
                    break;
               case 'extensions':
                    // $trkseg['extensions'] = static::process_extensions($child);
                    break;
            }
        }
        
        return $trkseg;
    }

    private static function process_time($node) {
        return $node->nodeValue;
    }

    private static function process_pt($node) {

        $pt = array();

        foreach ($node->attributes as $name => $value) {
            switch ($name) {
                case 'lat':
                    $pt['lat'] = $value->value;
                    break;
                case 'lon':
                    $pt['lon'] = $value->value;
                    break;
            }
        }

        foreach ($node->childNodes as $child) {
            switch ($child->nodeName) {
                case 'ele':
                    $pt['ele'] = $child->nodeValue;
                    break;
                case 'time':
                    $pt['timestamp'] = static::process_time($child);
                    break;
            }
        }

        return $pt;
    }

    private static function process_ptseg($node) {

        $ptseg = array();

        foreach ($node->childNodes as $child) {
            switch ($child->nodeName) {
                case 'pt':
                    $ptseg[] = static::process_pt($child);
                    break;
            }
        }

        return $ptseg;
    }


    /*********************************************************************************************
    MINIMUM CONVERTER
    **********************************************************************************************/

    private static function minimum($gps_data) {
        $data = array();

        foreach($gps_data as $key => $value) {
            switch($key) {
                case 'wpt':
                    $data['wpt'] = static::extract_names($value);
                    break;
                case 'rte':
                    $data['rte'] = static::extract_names($value);
                    break;
                case 'trk':
                    $data['trk'] = static::extract_names($value);
                    break;
            }
        }

        return $data;
    }

    private static function extract_names($data) {
        $points = array();

        foreach($data as $point_data) {
            $point = array();
            $point['gps_data_id'] = static::$_gps_counter++;
            foreach($point_data as $key => $value) {
                switch($key) {
                    case 'name':
                        $point['name'] = $value;
                        break;
                }
            }
            $points[] = $point;
        }

        return $points;
    }
}
