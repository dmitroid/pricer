<?php
class GenerateModel
{
    public function getPreparedArray($files, $template)
    {
        foreach ($files as $key => $file) {
            $files[$key]['data'] = $this->_csvToArray('storage/items/' . $file['name'], ',', $template['prices'][$key]['header']);
            $files[$key]['price'] = $this->_processFields($template['prices'][$key]['fields']);
        }

        $result = $this->_mergeFilesData($files);
        return $this->_prepareForCsv($result);
    }

    public function getHeader($files, $template)
    {
        $headers = [];

        foreach ($files as $key => $file) {
            $headers[] = $this->_sortByOrderInMain($template['prices'][$key]['fields']);
        }

        $header = [];
        foreach($headers as $item) {
            $header = $header + $item;
        }

        return $header;
    }

    protected function _prepareForCsv($data)
    {
        $result = [];
        foreach($data as $item) {
            unset($item['price']);
            ksort($item);
            $result[] = $item;
        }

        return $result;
    }

    protected function _mergeFilesData($files)
    {
        $files = $this->_sortByUnique($files);

        $result = [];
        for($i = 0; $i < count($files); $i++) {
            foreach($files[$i]['data'] as $unique => $item) {
                if ($i == 0) {
                    foreach($files[$i]['price'] as $key => $value) {
                        $result[$unique][$value['order_in_main']] = $item[$value['order'] - 1];
                        if ($key == 'price') {
                            $result[$unique]['price'] = $item[$value['order'] - 1];
                        }
                    }
                } else {
                    $temp = [];
                    foreach($files[$i]['price'] as $key => $value) {
                        $temp[$unique][$value['order_in_main']] = $item[$value['order'] - 1];
                        if ($key == 'price') {
                            $temp[$unique]['price'] = $item[$value['order'] - 1];
                        }
                    }

                    if (isset($result[$unique])) {
                        if ($temp[$unique]['price'] <= $result[$unique]['price']) {
                            $result[$unique] = $temp[$unique] + $result[$unique];
                        } else {
                            $result[$unique] = $result[$unique] + $temp[$unique];
                        }
                    } else {
                        $result[$unique] = $temp[$unique];
                    }
                }
            }
        }

        return $result;
    }

    protected function _sortByUnique($files)
    {
        foreach ($files as $key => $file) {
            $data = [];
            foreach ($file['data'] as $row) {
                $data[trim($row[$file['price']['unique']['order'] - 1])] = $row;
            }

            $files[$key]['data'] = $data;
        }

        return $files;
    }

    protected function _processFields($fields)
    {
        $processedFields = [];
        foreach ($fields as $field) {
            if ($field['is_unique'] == 1) {
                $processedFields['unique'] = $field;
            } elseif ($field['is_price'] == 1) {
                $processedFields['price'] = $field;
            } else {
                $processedFields[$field['order']] = $field;
            }
        }

        return $processedFields;
    }

    protected function _sortByOrderInMain($fields)
    {
        $processedFields = [];
        foreach ($fields as $field) {
            $processedFields[intval(trim($field['order_in_main']))] = $field['name'];
        }

        ksort($processedFields);
        return $processedFields;
    }

    protected function _csvToArray($filename = '', $delimiter = ',', $header)
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $data = [];
        if (($handle = fopen($filename, 'r')) !== false) {
            $i = 0;
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if ($header && !$i) {
                    $i++;
                    continue;
                }
                $data[] = $row;
            }
            fclose($handle);
        }
        return $data;
    }
}
