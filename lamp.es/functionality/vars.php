<?php 
        $list = getListFromDB();

        $bodyOutput = getListMarkup($list);
    
        $vmCSV = getArrayFromCsv("csv/vm.csv");
    
        $userCSV = getArrayFromCsv("csv/users.csv");
    
        $vmOutput = getTableFromCSVArray($vmCSV, true, true);
    
        $userOutput = getTableFromCSVArray($userCSV, true, true);
    
        $totalOutput = getTableFromCSVArray(CsvCombine($userCSV, $vmCSV), true, true);