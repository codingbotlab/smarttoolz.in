(() => {
  'use strict';

  const $ = (id) => document.getElementById(id);
  const uploadArea = $('uploadArea');
  const fileInput = $('fileInput');
  const fileInfo = $('fileInfo');
  const settings = $('settings');
  const quality = $('quality');
  const qualityValue = $('qualityValue');
  const compressBtn = $('compressBtn');
  const resetBtn = $('resetBtn');
  const errorBox = $('error');
  const result = $('result');
  const originalPreview = $('originalPreview');
  const compressedPreview = $('compressedPreview');
  const originalSize = $('originalSize');
  const compressedSize = $('compressedSize');
  const savedSize = $('savedSize');
  const downloadBtn = $('downloadBtn');

  if (!uploadArea || !fileInput || !compressBtn || !resetBtn) return;

  let selectedFile = null;
  let originalUrl = null;
  let compressedUrl = null;
  let busy = false;

  const showError = (message) => {
    if (!errorBox) return;
    errorBox.textContent = message;
    errorBox.style.display = 'block';
  };

  const clearError = () => {
    if (!errorBox) return;
    errorBox.textContent = '';
    errorBox.style.display = 'none';
  };

  const formatBytes = (bytes) => {
    if (!bytes || bytes <= 0) return '0 Bytes';
    const units = ['Bytes', 'KB', 'MB', 'GB'];
    const index = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
    return (bytes / Math.pow(1024, index)).toFixed(index === 0 ? 0 : 2) + ' ' + units[index];
  };

  const createFilename = (filename) => {
    const base = filename.replace(/\.[^/.]+$/, '').replace(/[^a-zA-Z0-9_-]/g, '-');
    return base + '-compressed.jpg';
  };

  const loadImage = (file) => new Promise((resolve, reject) => {
    const image = new Image();
    const url = URL.createObjectURL(file);
    image.onload = () => { URL.revokeObjectURL(url); resolve(image); };
    image.onerror = () => { URL.revokeObjectURL(url); reject(new Error('Unable to read this image.')); };
    image.src = url;
  });

  const canvasToBlob = (canvas, q) => new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', q));

  function handleFile(file) {
    clearError();
    if (!file || !['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
      showError('Please choose a JPG, JPEG, PNG or WebP image.');
      return;
    }
    if (file.size > 20 * 1024 * 1024) {
      showError('Maximum file size is 20 MB.');
      return;
    }

    selectedFile = file;
    fileInfo.textContent = file.name + ' • ' + formatBytes(file.size);
    if (originalUrl) URL.revokeObjectURL(originalUrl);
    originalUrl = URL.createObjectURL(file);
    originalPreview.src = originalUrl;
    settings.style.display = 'block';
    result.style.display = 'none';
  }

  async function compressImage(event) {
    event?.preventDefault();
    if (busy) return;
    if (!selectedFile) return showError('Please select an image first.');

    busy = true;
    compressBtn.disabled = true;
    resetBtn.disabled = true;
    compressBtn.textContent = 'Compressing...';
    clearError();

    try {
      const image = await loadImage(selectedFile);
      const canvas = document.createElement('canvas');
      canvas.width = image.naturalWidth;
      canvas.height = image.naturalHeight;
      const context = canvas.getContext('2d');
      if (!context) throw new Error('Your browser does not support image processing.');
      context.fillStyle = '#fff';
      context.fillRect(0, 0, canvas.width, canvas.height);
      context.drawImage(image, 0, 0);

      const q = Math.min(1, Math.max(0.1, Number.parseInt(quality.value, 10) / 100));
      const blob = await canvasToBlob(canvas, q);
      if (!blob) throw new Error('Could not create compressed image.');

      if (compressedUrl) URL.revokeObjectURL(compressedUrl);
      compressedUrl = URL.createObjectURL(blob);
      compressedPreview.src = compressedUrl;
      originalSize.textContent = formatBytes(selectedFile.size);
      compressedSize.textContent = formatBytes(blob.size);
      savedSize.textContent = Math.max(0, (1 - blob.size / selectedFile.size) * 100).toFixed(1) + '%';
      downloadBtn.href = compressedUrl;
      downloadBtn.download = createFilename(selectedFile.name);
      result.style.display = 'block';
      result.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } catch (error) {
      showError(error?.message || 'Compression failed.');
    } finally {
      busy = false;
      compressBtn.disabled = false;
      resetBtn.disabled = false;
      compressBtn.textContent = 'Compress Image';
    }
  }

  function resetTool(event) {
    event?.preventDefault();
    selectedFile = null;
    busy = false;
    if (originalUrl) URL.revokeObjectURL(originalUrl);
    if (compressedUrl) URL.revokeObjectURL(compressedUrl);
    originalUrl = compressedUrl = null;
    fileInput.value = '';
    fileInfo.textContent = '';
    originalPreview.removeAttribute('src');
    compressedPreview.removeAttribute('src');
    downloadBtn.removeAttribute('href');
    downloadBtn.removeAttribute('download');
    settings.style.display = 'none';
    result.style.display = 'none';
    quality.value = '80';
    qualityValue.textContent = '80%';
    clearError();
  }

  uploadArea.addEventListener('click', (event) => {
    event.preventDefault();
    fileInput.click();
  });
  fileInput.addEventListener('change', () => handleFile(fileInput.files?.[0]));
  uploadArea.addEventListener('dragover', (event) => { event.preventDefault(); uploadArea.classList.add('dragover'); });
  uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
  uploadArea.addEventListener('drop', (event) => {
    event.preventDefault();
    uploadArea.classList.remove('dragover');
    handleFile(event.dataTransfer.files?.[0]);
  });
  quality?.addEventListener('input', () => { qualityValue.textContent = quality.value + '%'; });
  compressBtn.addEventListener('click', compressImage);
  resetBtn.addEventListener('click', resetTool);
})();
