<?php
class BillRepository {
    private $file = 'bills.json';

    public function __construct() {
        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode([], JSON_PRETTY_PRINT));
        }
    }

    public function saveBill($title, $description, $author, $initial_draft,$status) {
        $bills = json_decode(file_get_contents($this->file), true);

        $newBill = [
            'id' => uniqid(),
            'title' => $title,
            'description' => $description,
            'author' => $author,
            'initial_draft' => $initial_draft,
            'created_at' => date("Y-m-d H:i:s"),
            'status' => $status
        ];

        $bills[] = $newBill;
        file_put_contents($this->file, json_encode($bills, JSON_PRETTY_PRINT));
    }

    public function getBills() {
        if (file_exists($this->file)) {
            return json_decode(file_get_contents($this->file), true);
        }
        return [];
    }
       // Get a bill by ID
       public function getBillById($id) {
        $bills = json_decode(file_get_contents($this->file), true);
        foreach ($bills as $bill) {
            if ($bill['id'] == $id) {
                return $bill;
            }
        }
        return null; // If bill not found
    }

    // Update an existing bill by ID
    public function updateBill($id, $title, $description, $initial_draft) {
        $bills = json_decode(file_get_contents($this->file), true);
        foreach ($bills as &$bill) {
            if ($bill['id'] == $id) {
                // Update the bill details
                $bill['title'] = $title;
                $bill['description'] = $description;
                $bill['initial_draft'] = $initial_draft;
                file_put_contents($this->file, json_encode($bills, JSON_PRETTY_PRINT));
                return true;
            }
        }
        return false; // If the bill is not found
    }
    public function makeAmendment($billId, $comment, $suggestedChanges) {
        // Load existing bills from the JSON file
        if (!file_exists($this->file)) {
            return "Bills file not found.";
        }
        
        $bills = json_decode(file_get_contents($this->file), true);
        $found = false;

        foreach ($bills as &$bill) {
            if ($bill["id"] == $billId) {
                if (!isset($bill["amendments"])) {
                    $bill["amendments"] = []; // Ensure amendments array exists
                }
                $bill["amendments"][] = [
                    "comment" => $comment,
                    "suggested_changes" => $suggestedChanges,
                    "timestamp" => date("Y-m-d H:i:s")
                ];
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            return "Bill ID not found.";
        }

        // Save the updated bills data
        file_put_contents($this->file, json_encode($bills, JSON_PRETTY_PRINT));
        return "Amendment suggested successfully!";
    }
}
?>
