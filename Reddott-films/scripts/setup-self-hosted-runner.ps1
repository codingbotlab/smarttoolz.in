# SmartToolz / Reddott self-hosted GitHub Actions runner setup
# Run this in an elevated PowerShell window on the Windows PC that will render videos.
# Never commit or paste the runner token into the repository.

$ErrorActionPreference = 'Stop'
$RepoUrl = 'https://github.com/codingbotlab/smarttoolz.in'
$RunnerDir = 'C:\actions-runner'
$RunnerName = 'SmartToolz-Reddott-Windows'
$Labels = 'self-hosted,windows,x64'

Write-Host '=== SmartToolz Reddott self-hosted runner setup ===' -ForegroundColor Cyan
Write-Host "Repository: $RepoUrl"
Write-Host "Runner directory: $RunnerDir"
Write-Host ''

function Require-Command([string]$Name, [string]$InstallHint) {
    if (-not (Get-Command $Name -ErrorAction SilentlyContinue)) {
        throw "$Name is not installed or not on PATH. $InstallHint"
    }
}

Require-Command 'git' 'Install Git for Windows first.'
Require-Command 'node' 'Install Node.js 20+ first.'
Require-Command 'npm' 'Install Node.js 20+ first.'
Require-Command 'python' 'Install Python 3.11+ first and enable the PATH option.'
Require-Command 'ffmpeg' 'Install FFmpeg and add its bin directory to PATH first.'
Require-Command 'ffprobe' 'Install FFmpeg and add its bin directory to PATH first.'

if (-not (Test-Path $RunnerDir)) {
    New-Item -ItemType Directory -Path $RunnerDir -Force | Out-Null
}
Set-Location $RunnerDir

if (-not (Test-Path (Join-Path $RunnerDir 'config.cmd'))) {
    Write-Host 'Downloading the latest GitHub Actions runner...' -ForegroundColor Yellow
    $headers = @{ 'User-Agent' = 'SmartToolz-Reddott-Runner-Setup' }
    $release = Invoke-RestMethod -Uri 'https://api.github.com/repos/actions/runner/releases/latest' -Headers $headers
    $asset = $release.assets | Where-Object { $_.name -match '^actions-runner-win-x64-.*\.zip$' } | Select-Object -First 1
    if (-not $asset) { throw 'Could not find the Windows x64 GitHub Actions runner package.' }
    $zip = Join-Path $RunnerDir 'actions-runner.zip'
    Invoke-WebRequest -Uri $asset.browser_download_url -OutFile $zip
    Expand-Archive -Path $zip -DestinationPath $RunnerDir -Force
    Remove-Item $zip -Force
}

Write-Host ''
Write-Host 'On GitHub open:' -ForegroundColor Green
Write-Host '  Repository -> Settings -> Actions -> Runners -> New self-hosted runner'
Write-Host 'Choose Windows + x64, then copy ONLY the time-limited token from the generated config command.'
Write-Host 'The token expires after about one hour. Do not commit it or send it to anyone.' -ForegroundColor Yellow
Write-Host ''

$Token = Read-Host 'Paste the temporary runner token here (input is hidden)'
if ([string]::IsNullOrWhiteSpace($Token)) { throw 'Runner token was empty.' }

if (Test-Path (Join-Path $RunnerDir '.runner')) {
    Write-Host 'Existing runner configuration detected. Removing it before re-registering...' -ForegroundColor Yellow
    & (Join-Path $RunnerDir 'config.cmd') remove --token $Token
    if ($LASTEXITCODE -ne 0) {
        Write-Host 'Old configuration could not be removed; continuing with fresh config.' -ForegroundColor Yellow
    }
}

Write-Host 'Registering runner...' -ForegroundColor Cyan
& (Join-Path $RunnerDir 'config.cmd') --unattended --url $RepoUrl --token $Token --name $RunnerName --labels $Labels --work '_work'
if ($LASTEXITCODE -ne 0) { throw 'Runner registration failed.' }

Write-Host 'Installing runner as a Windows service...' -ForegroundColor Cyan
& (Join-Path $RunnerDir 'svc.cmd') install
if ($LASTEXITCODE -ne 0) { throw 'Runner service installation failed. Run run.cmd manually if needed.' }

& (Join-Path $RunnerDir 'svc.cmd') start
if ($LASTEXITCODE -ne 0) { throw 'Runner service could not be started.' }

Write-Host ''
Write-Host 'SUCCESS: SmartToolz Reddott runner is installed and running.' -ForegroundColor Green
Write-Host 'Now go to GitHub -> Settings -> Actions -> Runners and confirm it shows Online.'
Write-Host 'The Reddott workflow is already configured to use this runner.'
