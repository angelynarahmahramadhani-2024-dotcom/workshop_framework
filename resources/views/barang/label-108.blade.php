<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Label Harga - Tom n Jerry 108</title>
<style>
@page {
    size: A4 portrait;
    margin: 10mm 5mm;
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.grid-container {
    width: 200mm;
    display: block;
}

.label-row {
    display: table;
    width: 100%;
    table-layout: fixed;
    margin: 0;
    padding: 0;
    border-collapse: collapse;
}

.label {
    display: table-cell;
    width: 38mm;
    height: 18mm;
    border: 1px solid #999;
    text-align: center;
    vertical-align: middle;
    padding: 1mm;
    margin: 0;
    position: relative;
    box-sizing: border-box;
}

.label-content {
    font-size: 7pt;
    line-height: 1.1;
}

.item-name {
    display: block;
    font-size: 6pt;
    font-weight: normal;
    margin-bottom: 0.5mm;
    color: #333;
}

.price-line {
    display: block;
    font-size: 7pt;
}

.currency {
    font-weight: normal;
    margin-right: 0.5mm;
}

.price {
    font-weight: bold;
}

/* X mark for used labels */
.marked::before,
.marked::after {
    content: '';
    position: absolute;
    width: 50%;
    height: 0.8px;
    background: #888;
    top: 50%;
    left: 50%;
}

.marked::before {
    transform: translate(-50%, -50%) rotate(45deg);
}

.marked::after {
    transform: translate(-50%, -50%) rotate(-45deg);
}
</style>
</head>
<body>
<div class="grid-container">
@php
$cols = 5;
$rows = 8;
$dataIndex = 0;
$startCol = $x;
$startRow = $y;
$startNumber = ($startRow - 1) * $cols + $startCol;
@endphp

@for($row = 1; $row <= $rows; $row++)
    <div class="label-row">
    @for($col = 1; $col <= $cols; $col++)
        @php
            $labelNumber = ($row - 1) * $cols + $col;
        @endphp
        
        @if($labelNumber < $startNumber)
            <div class="label marked"></div>
        @elseif(isset($barangs[$dataIndex]))
            <div class="label">
                <div class="label-content">                    <span class="item-name">{{ $barangs[$dataIndex]->nama }}</span>                    <span class="price-line">
                        <span class="currency">Rp</span><span class="price">{{ number_format($barangs[$dataIndex]->harga, 0, ',', '.') }}</span>
                    </span>
                </div>
            </div>
            @php $dataIndex++; @endphp
        @else
            <div class="label"></div>
        @endif
    @endfor
    </div>
@endfor
</div>
</body>
</html>
