/* SmartToolz Image Compressor - browser-only compression */
(() => {
  'use strict';

  const $ = id => document.getElementById(id);
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

  if (!compressBtn || !fileInput || !uploadArea) return;

  let selectedFile = null;
  let originalUrl = null;
  let compressedUrl = null;

  function showError(message) {
    if (!errorBox) return;
    errorBox.textContent = message;
    errorBox.style.display = 'block';
  }

  function clearError() {
    if (!errorBox) return;
    errorBox.textContent = '';
    errorBox.style.display = 'none';
  }

  function formatBytes(bytes) {
    if (bytes <= 0) return '0 Bytes';
    const units = ['Bytes', 'KB', 'MB', 'GB'];
    const index = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
    const value = bytes / Math.pow(1024, index);
    return value.toFixed(index === 0 ? 0 : 2) + ' ' + units[index];
  }

  function createFilename(filename) {
    return filename
      .replace(/\.[^/.]+$/, '')
      .replace(/[^a-zA-Z0-9_-]/g, '-') + '-compressed.jpg';
  }

  function loadImage(file) {
    return new Promise((resolve, reject) => {
      const image = new Image();
      const objectUrl = URL.createObjectURL(file);

      image.onload = () => {
        URL.revokeObjectURL(objectUrl);
        resolve(image);
      };

      image.onerror = () => {
        URL.revokeObjectURL(objectUrl);
        reject(new Error('Unable to read this image.'));
      };

      image.src = objectUrl;
    });
  }

  function canvasToBlob(canvas, type, qualityValue) {
    return new Promise(resolve => canvas.toBlob(resolve, type, qualityValue));
  }

  function handleFile(file) {
    clearError();

    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

    if (!allowedTypes.includes(file.type)) {
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

  uploadArea.addEventListener('click', event => {
    event.preventDefault();
    event.stopPropagation();
    fileInput.click();
  });

  fileInput.addEventListener('change', event => {
    event.preventDefault();
    if (event.target.files && event.target.files[0]) {
      handleFile(event.target.files[0]);
    }
  });

  uploadArea.addEventListener('dragover', event => {
    event.preventDefault();
    event.stopPropagation();
    uploadArea.classList.add('dragover');
  });

  uploadArea.addEventListener('dragleave', event => {
    event.preventDefault();
    uploadArea.classList.remove('dragover');
  });

  uploadArea.addEventListener('drop', event => {
    event.preventDefault();
    event.stopPropagation();
    uploadArea.classList.remove('dragover');
    if (event.dataTransfer.files && event.dataTransfer.files[0]) {
      handleFile(event.dataTransfer.files[0]);
    }
  });

  quality.addEventListener('input', () => {
    qualityValue.textContent = quality.value + '%';
  });

  compressBtn.addEventListener('click', async event => {
    event.preventDefault();
    event.stopPropagation();

    if (!selectedFile) {
      showError('Please select an image first.');
      return false;
    }

    if (compressBtn.disabled) return false;

    clearError();
    compressBtn.disabled = true;
    compressBtn.textContent = 'Compressing...';

    try {
      const image = await loadImage(selectedFile);
      const canvas = document.createElement('canvas');
      canvas.width = image.naturalWidth;
      canvas.height = image.naturalHeight;

      const context = canvas.getContext('2d');
      if (!context) throw new Error('Your browser does not support image processing.');

      // JPEG does not support transparency, so use a white background.
      context.fillStyle = '#ffffff';
      context.fillRect(0, 0, canvas.width, canvas.height);
      context.drawImage(image, 0, 0);

      const compressionQuality = parseInt(quality.value, 10) / 100;
      const blob = await canvasToBlob(canvas, 'image/jpeg', compressionQuality);

      if (!blob) throw new Error('Could not create compressed image.');

      if (compressedUrl) URL.revokeObjectURL(compressedUrl);
      compressedUrl = URL.createObjectURL(blob);

      compressedPreview.src = compressedUrl;
      originalSize.textContent = formatBytes(selectedFile.size);
      compressedSize.textContent = formatBytes(blob.size);

      const reduction = (1 - (blob.size / selectedFile.size)) * 100;
      savedSize.textContent = Math.max(0, reduction).toFixed(1) + '%';

      downloadBtn.href = compressedUrl;
      downloadBtn.download = createFilename(selectedFile.name);
      downloadBtn.setAttribute('download', createFilename(selectedFile.name));

      result.style.display = 'block';
      result.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } catch (error) {
      showError(error.message || 'Compression failed.');
    } finally {
      compressBtn.disabled = false;
      compressBtn.textContent = 'Compress Image';
    }

    return false;
  });

  resetBtn.addEventListener('click', event => {
    event.preventDefault();
    event.stopPropagation();

    selectedFile = null;

    if (originalUrl) URL.revokeObjectURL(originalUrl);
    if (compressedUrl) URL.revokeObjectURL(compressedUrl);
    originalUrl = null;
    compressedUrl = null;

    fileInput.value = '';
    fileInfo.textContent = '';
    originalPreview.removeAttribute('src');
    compressedPreview.removeAttribute('src');
    settings.style.display = 'none';
    result.style.display = 'none';
    quality.value = '80';
    qualityValue.textContent = '80%';
    clearError();
  });
})();
