/* ===========================================================
   card-render.js — builds the front/back ID card HTML
   Shared by settings.php (live preview) and id-generator.php
   =========================================================== */

function currentSettingsFromForm() {
  return {
    logo: window.__existingLogo || '',
    background: window.__existingBg || '',
    primaryColor: document.getElementById('s_primaryColor')?.value || '#4f46e5',
    secondaryColor: document.getElementById('s_secondaryColor')?.value || '#06b6d4',
    fontFamily: document.getElementById('s_fontFamily')?.value || 'Poppins, sans-serif',
    fontSize: document.getElementById('s_fontSize')?.value || 14,
    showQrCode: document.getElementById('s_showQrCode')?.checked ?? true,
    showBarcode: document.getElementById('s_showBarcode')?.checked ?? true,
    barcodePosition: document.getElementById('s_barcodePosition')?.value || 'bottom',
    barcodeOrientation: document.getElementById('s_barcodeOrientation')?.value || 'horizontal',
    barcodeOffsetX: document.getElementById('s_barcodeOffsetX')?.value || 0,
    barcodeOffsetY: document.getElementById('s_barcodeOffsetY')?.value || 0,
    photoSize: document.getElementById('s_photoSize')?.value || 90,
    borderRadius: document.getElementById('s_borderRadius')?.value || 16,
    shadow: document.getElementById('s_shadow')?.checked ?? true,
    cardWidth: document.getElementById('s_cardWidth')?.value || 340,
    cardHeight: document.getElementById('s_cardHeight')?.value || 214,
    orientation: document.getElementById('s_orientation')?.value || 'portrait',
    photoPosition: document.getElementById('s_photoPosition')?.value || 'left',
    logoPosition: document.getElementById('s_logoPosition')?.value || 'left',
    companyName: document.getElementById('s_companyName')?.value || 'Smart ID Systems',
    instituteName: document.getElementById('s_instituteName')?.value || 'Greenfield Institute',
    address: document.getElementById('s_address')?.value || '',
    website: document.getElementById('s_website')?.value || '',
    email: document.getElementById('s_email')?.value || '',
    phone: document.getElementById('s_phone')?.value || '',
    cardHeading: document.getElementById('s_cardHeading')?.value || '',
    footerText: document.getElementById('s_footerText')?.value || '',
    layoutStyle: window.__layoutStyle || '',
    cardSideMode: window.__cardSideMode || 'double',
    qrPosition: window.__qrPosition || 'footer',
  };
}

const DEMO_RECORD = {
  id: 'SCH0001',
  name: 'Aakash Kumar',
  photo: '',
  class: '10', section: 'A', roll_number: '21',
  blood_group: 'O+',
  status: 'Active',
  phone: '+91 90000 00001',
  issue_date: '2026-01-01',
  expiry_date: '2027-01-01',
  school_name: 'Greenfield School',
  principal_name: 'Dr. R. Sharma',
};

function placeholderAvatar(size) {
  return `data:image/svg+xml;utf8,${encodeURIComponent(
    `<svg xmlns="http://www.w3.org/2000/svg" width="${size}" height="${size}"><rect width="${size}" height="${size}" rx="12" fill="rgba(255,255,255,0.25)"/><text x="50%" y="58%" font-size="${size * 0.5}" text-anchor="middle">🙂</text></svg>`
  )}`;
}

/**
 * Render the front + back of an ID card as HTML string.
 * @param {object} settings
 * @param {object} record  a module record (or DEMO_RECORD)
 * @param {string} moduleKey
 */
function renderIdCard(settings, record, moduleKey = 'school') {
  const isLandscape = settings.orientation === 'landscape';
  const orientationClass = isLandscape ? 'landscape' : '';

  let cardWidth = parseInt(settings.cardWidth) || (isLandscape ? 420 : 340);
  let cardHeight = parseInt(settings.cardHeight) || (isLandscape ? 250 : 214);

  // Automatically adjust dimensions if orientation mismatched with width/height ratio
  if (isLandscape && cardWidth < cardHeight) {
    [cardWidth, cardHeight] = [cardHeight, cardWidth];
  } else if (!isLandscape && cardWidth > cardHeight) {
    [cardWidth, cardHeight] = [cardHeight, cardWidth];
  }

  const photo = record.photo || placeholderAvatar(settings.photoSize);
  const shadowStyle = settings.shadow ? '' : 'box-shadow:none;';
  const defaultLabel = { school: 'SCHOOL ID CARD', college: 'COLLEGE ID CARD', office: 'EMPLOYEE ID CARD', hospital: 'PATIENT ID CARD' }[moduleKey] || 'ID CARD';
  const orgLabel = settings.cardHeading ? settings.cardHeading : defaultLabel;
  const layoutStyleClass = settings.layoutStyle === 'holder-wave' ? 'holder-wave-layout' : '';
  const qrPosition = settings.qrPosition || 'footer';
  const barcodePosition = settings.barcodePosition || 'bottom';
  const barcodeOrientation = settings.barcodeOrientation || 'horizontal';
  const barcodeOffsetX = Math.max(-180, Math.min(180, Number(settings.barcodeOffsetX) || 0));
  const barcodeOffsetY = Math.max(-140, Math.min(140, Number(settings.barcodeOffsetY) || 0));

  const infoRows = buildInfoRows(record, moduleKey);

  const logoSrc = record.logo || settings.logo;
  const moduleIcon = { school: 'bi-mortarboard-fill', college: 'bi-bank', office: 'bi-building-fill', hospital: 'bi-hospital-fill' }[moduleKey] || 'bi-person-badge-fill';
  const orgLogoHtml = logoSrc ? `<img src="${logoSrc}" style="width:100%;height:100%;object-fit:cover;border-radius:8px;">` : `<i class="bi ${moduleIcon}"></i>`;
  const logoPosition = settings.logoPosition || 'left';
  const headerStyle = logoPosition === 'center'
    ? 'flex-direction:column;justify-content:center;text-align:center;gap:5px;'
    : logoPosition === 'right'
      ? 'flex-direction:row-reverse;text-align:right;'
      : '';
  const logoHtml = (logoPosition === 'hidden' || logoPosition === 'bottom') ? '' : `<div class="org-logo">${orgLogoHtml}</div>`;
  const bottomLogoHtml = logoPosition === 'bottom' ? `<div class="bottom-org-logo">${orgLogoHtml}</div>` : '';
  const bodyQrHtml = settings.showQrCode && qrPosition !== 'footer'
    ? `<div class="qr-feature"><div class="qr-box" id="qrTarget-${record.id}" data-qr-size="82"></div></div>`
    : '';
  const footerQrHtml = settings.showQrCode && qrPosition === 'footer'
    ? `<div class="qr-box" id="qrTarget-${record.id}" data-qr-size="46"></div>`
    : '';
  const barcodeHtml = settings.showBarcode
    ? `<div class="barcode-box barcode-${barcodePosition} barcode-${barcodeOrientation}" style="--barcode-x:${barcodeOffsetX}px;--barcode-y:${barcodeOffsetY}px;"><svg id="barcodeTarget-${record.id}"></svg></div>`
    : '';

  // Use each student's specific registered School/College/Office/Hospital name
  const studentOrgName = record.school_name || record.college_name || record.office_name || record.hospital_name || settings.instituteName || settings.companyName || 'ID CARD';
  const mainTitleHtml = `<div class="org-name" style="font-size:13.5px;font-weight:800;line-height:1.2;">${studentOrgName}</div>`;

  const photoPos = settings.photoPosition || 'center';
  let bodyStyle = '';
  let infoStyle = '';
  if (photoPos === 'center') {
    bodyStyle = 'flex-direction:column;align-items:center;text-align:center;gap:8px;margin-top:10px;';
    infoStyle = 'display:flex;flex-direction:column;align-items:center;text-align:center;width:100%;';
  } else if (photoPos === 'bottom') {
    bodyStyle = 'flex-direction:column-reverse;align-items:center;text-align:center;gap:8px;margin-top:10px;';
    infoStyle = 'display:flex;flex-direction:column;align-items:center;text-align:center;width:100%;';
  } else if (photoPos === 'right') {
    bodyStyle = 'flex-direction:row-reverse;align-items:flex-start;';
    infoStyle = 'text-align:left;';
  } else {
    bodyStyle = 'flex-direction:row;align-items:flex-start;';
    infoStyle = 'text-align:left;';
  }

  const front = `
    <div class="id-card ${orientationClass} ${layoutStyleClass}" style="--card-primary:${settings.primaryColor};--card-secondary:${settings.secondaryColor};width:${cardWidth}px;min-height:${cardHeight}px;border-radius:${settings.borderRadius}px;font-family:${settings.fontFamily};font-size:${settings.fontSize}px;${shadowStyle}">
      <div class="id-card-header" style="${headerStyle}">
        ${logoHtml}
        <div>
          ${mainTitleHtml}
          <div class="org-sub">${orgLabel}</div>
        </div>
      </div>
      <div class="id-card-body" style="${bodyStyle}">
        ${qrPosition === 'body-left' ? bodyQrHtml : ''}
        <img src="${photo}" class="id-card-photo" style="width:${settings.photoSize}px;height:${settings.photoSize}px;">
        <div class="id-card-info" style="${infoStyle}">
          <p class="cname">${record.name || 'Full Name'}</p>
          <p class="cid">${record.id || 'ID0000'}</p>
          ${infoRows}
        </div>
        ${qrPosition === 'body-right' ? bodyQrHtml : ''}
      </div>
      ${bottomLogoHtml}
      <div class="id-card-footer">
        <span style="font-size:9px;opacity:.85;">${settings.footerText ? settings.footerText.slice(0, 40) : ''}</span>
        <span class="signature-mini">Authorized Signature</span>
        <div class="codes">
          ${footerQrHtml}
        </div>
      </div>
    </div>`;

  const back = `
    <div class="id-card id-card-back ${orientationClass} ${layoutStyleClass}" style="--card-primary:${settings.primaryColor};--card-secondary:${settings.secondaryColor};width:${cardWidth}px;min-height:${cardHeight}px;border-radius:${settings.borderRadius}px;font-family:${settings.fontFamily};font-size:${settings.fontSize}px;${shadowStyle}">
      <div class="back-content">
        <p style="margin:0 0 8px;font-weight:700;">${settings.companyName}</p>
        <p style="margin:0;">${settings.address || ''}</p>
        <p style="margin:0;">${settings.website || ''} ${settings.email ? '· ' + settings.email : ''}</p>
        <p style="margin:0;">${settings.phone || ''}</p>
        <p style="margin:10px 0 0;">${settings.footerText || ''}</p>
        ${barcodeHtml}
        <div class="sig-line">Authorized Signature</div>
      </div>
    </div>`;

  return { front, back };
}

function formatYearOnly(dateStr) {
  if (!dateStr) return '';
  const str = String(dateStr).trim();
  if (/^\d{4}/.test(str)) {
    return str.substring(0, 4);
  }
  return str;
}

function buildInfoRows(record, moduleKey) {
  const rowMap = {
    school: [['class', 'Class'], ['section', 'Sec'], ['blood_group', 'Blood']],
    college: [['department', 'Dept'], ['year', 'Year'], ['blood_group', 'Blood']],
    office: [['department', 'Dept'], ['designation', 'Role'], ['blood_group', 'Blood']],
    hospital: [['doctor_name', 'Doctor'], ['blood_group', 'Blood'], ['emergency_contact', 'Emergency']],
  };
  const rows = rowMap[moduleKey] || [];
  let html = rows.map(([key, label]) => record[key] ? `<div class="crow"><b>${label}:</b> ${record[key]}</div>` : '').join('');

  const issueYear = formatYearOnly(record.issue_date);
  const expiryYear = formatYearOnly(record.expiry_date);
  let yearDisplay = '';
  if (issueYear && expiryYear) {
    yearDisplay = (issueYear === expiryYear) ? issueYear : `${issueYear} - ${expiryYear}`;
  } else if (issueYear) {
    yearDisplay = issueYear;
  } else if (expiryYear) {
    yearDisplay = expiryYear;
  }

  if (yearDisplay) {
    html += `<div class="crow"><b>Year:</b> ${yearDisplay}</div>`;
  }

  return html;
}

/** Draw QR code + barcode into the rendered card using CDN libs (call after inserting HTML into DOM) */
function drawCodes(settings, record) {
  if (settings.showQrCode && window.QRCode) {
    const qrTarget = document.getElementById(`qrTarget-${record.id}`);
    if (qrTarget) {
      qrTarget.innerHTML = '';
      new QRCode(qrTarget, {
        text: JSON.stringify({ id: record.id, name: record.name, phone: record.phone, org: record.school_name || record.college_name || record.office_name || record.hospital_name || '' }),
        width: Number(qrTarget.dataset.qrSize) || 46, height: Number(qrTarget.dataset.qrSize) || 46, correctLevel: QRCode.CorrectLevel.M
      });
    }
  }
  if (settings.showBarcode && window.JsBarcode) {
    const barTarget = document.getElementById(`barcodeTarget-${record.id}`);
    if (barTarget) {
      try {
        JsBarcode(barTarget, record.id || '0000', { width: 1.4, height: 30, displayValue: false, margin: 0 });
      } catch (e) { /* ignore invalid values */ }
    }
  }
}
