<?php

$nationalizeApiUrl = "https://api.nationalize.io?name=";
$agifyApiUrl = "https://api.agify.io?name=";
$genderizeApiUrl = "https://api.genderize.io?name=";
$countriesFilePath = "countries.json";

if (file_exists($countriesFilePath)) {
    $countriesJson = file_get_contents($countriesFilePath);
    $countryCodeToNameMap = json_decode($countriesJson);
} else {
    exit("Country data file not found.\n");

}

while (true) {
    echo "What would you like to find out about your name?: \n";
    echo "Enter 1 for nationality \n";
    echo "Enter 2 for age \n";
    echo "Enter 3 for gender \n";
    echo "Enter 4 to exit \n";

    $userChoice = readline("Please enter your choice: ");

    switch ($userChoice) {
        case 1:
            $name = readline("Please enter your name: ");
            $nationalizeResponse = file_get_contents($nationalizeApiUrl . urlencode($name));
            if ($nationalizeResponse === false) {
                echo "Error fetching nationality data. Please try again.\n";
                break;
            }
            $nationalizeData = json_decode($nationalizeResponse);
            if (isset($nationalizeData->country) && count($nationalizeData->country) > 0) {
                foreach ($nationalizeData->country as $countryInfo) {
                    $countryName = $countryCodeToNameMap->{$countryInfo->country_id} ?? "Unknown country";
                    echo "$name is likely from $countryName with probability: "
                        . round($countryInfo->probability * 100, 2) . "%\n";
                }
            } else {
                echo "No nationality information found for the name $name.\n";
            }
            break;

        case 2:
            $name = readline("Please enter your name: ");
            $agifyResponse = file_get_contents($agifyApiUrl . urlencode($name));
            if ($agifyResponse === false) {
                echo "Error fetching age data. Please try again.\n";
                break;
            }
            $agifyData = json_decode($agifyResponse);
            if (isset($agifyData->age)) {
                echo "Estimated age for $name is: " . $agifyData->age . " years old.\n";
            } else {
                echo "No age information found for the name $name.\n";
            }
            break;

        case 3:
            $name = readline("Please enter your name: ");
            $genderizeResponse = file_get_contents($genderizeApiUrl . urlencode($name));
            if ($genderizeResponse === false) {
                echo "Error fetching gender data. Please try again.\n";
                break;
            }
            $genderizeData = json_decode($genderizeResponse);
            if (isset($genderizeData->gender)) {
                echo "$name is " . $genderizeData->gender . " with a probability of "
                    . round($genderizeData->probability * 100, 2) . "%\n";
            } else {
                echo "No gender information found for the name $name.\n";
            }
            break;

        case 4:
            echo "Exiting the program.\n";
            exit();

        default:
            echo "Invalid choice. Please enter 1, 2, 3, or 4.\n";
            break;
    }
}
