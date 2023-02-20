<?php

include 'constants/CsvConstant.php';

/**
 * Class FinalResult
 */
class FinalResult {

    /**
     * @param $file
     * @return array
     * @throws Exception
     */
    public function results($file): array
    {
        $handle = fopen($file, "r");
        if ($handle === false) {
            throw new Exception("Unable to open file");
        }

        $headers = fgetcsv($handle);
        if ($headers === false) {
            throw new Exception("Unable to read header row");
        }

        $records = $this->processRows($handle, $headers);

        fclose($handle);
        return [
            "filename" => basename($file),
            "document" => $handle,
            "failure_code" => $headers[CsvConstant::FAILURE_CODE],
            "failure_message" => $headers[CsvConstant::FAILURE_MESSAGE],
            "records" => $records
        ];
    }

    /**
     * @param $handle
     * @param $headers
     * @return array
     */
    private function processRows($handle, $headers): array
    {
        $records = [];
        while (!feof($handle)) {
            $row = fgetcsv($handle);
            if(count($row) == 16) {
                $records[] = $this->processRow($row, $headers);
            }
        }
        return $records;
    }

    /**
     * @param $row
     * @param $headers
     * @return array
     */
    private function processRow($row, $headers): array
    {
        $amount = !$row[CsvConstant::AMOUNT] || $row[CsvConstant::AMOUNT] == "0" ? 0 : (float) $row[CsvConstant::AMOUNT];
        $bankAccountNumber = !$row[CsvConstant::BANK_ACCOUNT_NUMBER] ? "Bank account number missing" : (int) $row[CsvConstant::BANK_ACCOUNT_NUMBER];
        $bankBranchCode = !$row[CsvConstant::BANK_BRANCH_CODE] ? "Bank branch code missing" : $row[CsvConstant::BANK_BRANCH_CODE];
        $endToEndId = !$row[CsvConstant::END_TO_END_ID_FIRST] && !$row[CsvConstant::END_TO_END_ID_LAST] ? "End to end id missing" : $row[CsvConstant::END_TO_END_ID_FIRST] . $row[CsvConstant::END_TO_END_ID_LAST];

        return [
            "amount" => [
                "currency" => $headers[CsvConstant::CURRENCY],
                "subunits" => (int) ($amount * 100)
            ],
            "bank_account_name" => str_replace(" ", "_", strtolower($row[CsvConstant::BANK_ACCOUNT_NAME])),
            "bank_account_number" => $bankAccountNumber,
            "bank_branch_code" => $bankBranchCode,
            "bank_code" => $row[CsvConstant::BANK_CODE],
            "end_to_end_id" => $endToEndId,
        ];
    }
}