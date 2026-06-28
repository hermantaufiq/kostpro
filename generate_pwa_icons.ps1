Add-Type -AssemblyName System.Drawing

$srcPath = "C:\Users\ASUS\.gemini\antigravity-ide\brain\807c12dd-6f48-4b9a-a2b2-55c0e9706bd9\kospro_pwa_icon_1782306851426.png"
$destDir = "C:\laragon\www\kostpro\public\icons"

if (!(Test-Path $destDir)) {
    New-Item -ItemType Directory -Path $destDir -Force | Out-Null
}

$sizes = @(72, 96, 128, 144, 152, 192, 384, 512)

$srcBitmap = [System.Drawing.Bitmap]::new($srcPath)
Write-Host "Source: $($srcBitmap.Width)x$($srcBitmap.Height)"

foreach ($size in $sizes) {
    $dst = [System.Drawing.Bitmap]::new($size, $size)
    $g = [System.Drawing.Graphics]::FromImage($dst)
    $g.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
    $g.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::HighQuality
    $g.PixelOffsetMode = [System.Drawing.Drawing2D.PixelOffsetMode]::HighQuality
    $g.DrawImage($srcBitmap, 0, 0, $size, $size)
    $g.Dispose()
    
    $outPath = Join-Path $destDir "icon-${size}x${size}.png"
    $dst.Save($outPath, [System.Drawing.Imaging.ImageFormat]::Png)
    $dst.Dispose()
    
    $sizeKB = [math]::Round((Get-Item $outPath).Length / 1024, 1)
    Write-Host "Generated: icon-${size}x${size}.png ($sizeKB KB)"
}

# Maskable icon (copy dari 512)
Copy-Item (Join-Path $destDir "icon-512x512.png") (Join-Path $destDir "maskable-icon-512x512.png") -Force
Write-Host "Generated: maskable-icon-512x512.png"

$srcBitmap.Dispose()
Write-Host "`nDone! All PWA icons generated in $destDir"
