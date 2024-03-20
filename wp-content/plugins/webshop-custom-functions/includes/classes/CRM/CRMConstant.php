<?php

class CRMConstant
{
    const FUNCTION_CONTACT = array(
            "Z41" => "Accounting",
            "Z42" => "Administrative",
            "Z43" => "Business Development",
            "Z44" => "Consulting",
            "Z45" => "Engineering",
            "Z46"=> "Finance",
            "Z47"=>"Human Resources",
            "Z48"=>"Information Technology",
            "Z49"=>"Marketing",
            "Z50"=>"Operations",
            "Z58"=>"Legal",
            "Z52"=>"Military and Protective Services",
            "Z51"=>"Product Management",
            "Z52"=>"Purchasing",
            "Z53"=>"Quality Assurance",
            "Z54"=>"Research",
            "Z55"=>"Sales",
            "Z56"=>"Support",
        );

    const FUNCTION_FIELD = array(
        "0001" => "Purchasing",
        "0002" => "Sales",
        "0003" => "Administration",
        "0005" => "QA Assurance",
        "0006" => "Secretary's Office",
        "0007" => "Financial",
        "0008" => "Legal",
        "0018" => "R&D",
        "0019" => "Product Dev",
        "Z020" => "Executive Board",
        "Z021" => "Packaging Dev",
        "Z022" => "Production",
        "Z023" => "Quality Control Dept",
        "Z024" => "Logistics",
        "Z025" => "Operations",
        "Z026" => "Advanced Pur",
        "Z027" => "Consulting",
        "Z28" => "IT",
        "Z29" => "Marketing",
        "Z30" => "Customer Ser",
        "Z31" => "Audit",
        "Z32" => "HR",
        "Z33" => "Engineering",
        "Z34" => "Project Management",
        "Z35" => "Laboratory",
        "Z36" => "Procurement",
        "ZSC" => "Supply Chain",
    );

    const MATERIAL = array(
        'CR' => 'CHLOROPRENE RUBBER - CR (Neoprene™)',
        'EPDM' => 'ETHYLENE-PROPYLENE-DIENE RUBBER - EPDM',
        'FKM' => 'FLUOROCARBON RUBBER - FKM',
        'FVMQ' => 'FLUOROSILICONE-FVMQ',
        'HNBR' => 'HYDROGENATED NITRILE - HNBR',
        'NBR' => 'NITRILE BUTADIENE RUBBER - NBR',
        'TFP' => 'TETRAFLUOROETHYLENE PROPYLENE - TFP (Aflas®)',
        'VMQ' => 'SILICONE RUBBER - VMQ	',
    );

    const TITLE = array(
        '0001' => "Ms.",
        '0002' => "Mr.",
    );

/*
    const PRODUCT = array(
        '141' => "Custom Molded Rubber Seals",
        '151' => "Rubber to Metal Bonded Seals",
        '171' => "Machined Thermoplastic",
        //'311' => "None",
        '321' => "O-Ring",
        '331' => "Rubber to Plastic Bonded Seals",
        '341' => "Custom Machined Metal Parts",
        '351' => "Molded Resins",
        '361' => "Surface Production Equipment",
        '371' => "Wearable Sensors"
    );
*/
    const PRODUCT = array(
        '002' => "Custom Molded Rubber Seals",
        '006' => "Rubber to Metal Bonded Seals",
        '003' => "Machined Thermoplastic",
        //'311' => "None",
        '005' => "O-Ring",
        '007' => "Rubber to Plastic Bonded Seals",
        '001' => "Custom Machined Metal Parts",
        '004' => "Molded Resins",
        '008' => "Surface Production Equipment",
        '371' => "Wearable Sensors"
    );
    const COMPOUND = array(
        'Chemical Resistant',
        'Oil Resistant',
        'Water and Steam Resistant',
    );

    const HARDNESS = array(
        '40A',
        '50A',
        '55A',
        '60A',
        '65A',
        '70A',
        '75A',
        '80A',
        '85A',
        '90A',
        '95A',
        '50D',
        '55D',
        '60D',
        '65D',
    );
}
