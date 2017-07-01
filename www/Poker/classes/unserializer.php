<?php 
class UnSerializer {

    public function __construct($filename_with_path) { /* Input the Filename  */
        $this->filename = $filename_with_path;
        if ($this->filename == true) {
            return true;
        } else {
            echo 'File Name Error';
        }
    }

    public function check_file_validity() {
        $this->validity = file_exists($this->filename);
        if ($this->validity == true) {
            return true;
        } else {
            echo 'File Not Found !';
        }
    }

    public function getting_file_content() {
        if ($this->validity == true) {
            $this->content = file_get_contents($this->filename);
            if ($this->content == true) {
                return true;
            } else {
                echo 'We Can\'t Reach to the Data';
            }
        } else {
            echo 'File Not Found !';
        }
    }

    public function get_unserial_data() {
        $this->check_file_validity();
        $this->getting_file_content();
        if (!is_null($this->content)) {
            $this->unserializedval = unserialize($this->content);
            if ($this->unserializedval == true) {
                return true;
            }
        } else {
            echo 'We Can\'t Reach to the Data';
        }
    }

    public function get_unserialized_value() {
        return $this->unserializedval;
    }

}