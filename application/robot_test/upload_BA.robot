*** Settings ***
Library    SeleniumLibrary
Suite Setup    Open Browser KJP
Suite Teardown    Close Browser

*** Variables ***
${URL}          https://edu.jakarta.go.id/kjp/login
${BROWSER}      chrome
${USERNAME}     20105562
${PASSWORD}     444444
${ROWS}       xpath=//table//tr[td]
${TIMEOUT}        20s
${UPLOAD_BTN}    //tbody[@id='data']//button[@title='Unggah Berkas']
${TOTAL_ROWS}    Get Element Count    xpath=//tbody[@id='data']//tr


*** Keywords ***
Open Browser KJP
    Open Browser    ${URL}    ${BROWSER}
    Maximize Browser Window
    Sleep    2s

*** Test Cases ***
Login Success
    Input Text    name=login   ${USERNAME}
    Input Text    name=password    ${PASSWORD}
    Click Button  id=login-btn
    Sleep    2s

Go to Menu Verifikasi KJP
    Wait Until Page Contains   Aplikasi    10s
    Click Link    Aplikasi
    Wait Until Element Is Visible    css=.card-body    10s
    Click Element     css=.card-body
    Wait Until Element Is Visible    xpath=//a[.//h4[normalize-space()='Verifikasi Sekolah ( Lanjutan )']]    20s
    Scroll Element Into View         xpath=//a[.//h4[normalize-space()='Verifikasi Sekolah ( Lanjutan )']]
    Click Element                    xpath=//a[.//h4[normalize-space()='Verifikasi Sekolah ( Lanjutan )']]
    Wait Until Page Contains    Verifikasi Lanjutan    60s


Upload All BA
    # Pindah halaman
    Click Element    xpath=//select[@id='size']
    Click Element    xpath=//option[@value='500']
    Press Keys    None    ESC
    Sleep    5s

    # Hitung tombol upload di halaman aktif
    ${total}=    Get Element Count    xpath=${UPLOAD_BTN}

    # ================= ADA DATA → PROSES UPLOAD =================
    FOR    ${i}    IN RANGE    1    ${total}+1
        ${row_html}=    Get Element Attribute
        ...    xpath=(//tbody[@id='data']//tr)[${i}]
        ...    outerHTML
        
        # Scroll ke baris ke-i dulu (WAJIB)
        Scroll Element Into View
        ...    xpath=(//tbody[@id='data']//tr)[${i}]

        # Baru ambil nama siswa
        ${nama_siswa}=    Get Text
        ...    xpath=(//tbody[@id='data']//tr)[${i}]//td[3][normalize-space()]

        Log    Upload untuk siswa: ${nama_siswa}

        # === CEK STATUS BERKAS ===
        ${skip}=    Run Keyword And Return Status
            ...    Page Should Contain Element
            ...    xpath=(//tbody[@id='data']//tr)[${i}]//span[contains(text(),'Berkas Lengkap') or contains(text(),'Berkas Dalam Verifikasi')]


        IF    ${skip}
            Log    SKIP ${nama_siswa} - Status Berkas
            Continue For Loop
        END


        ${btn}=    Set Variable    (${UPLOAD_BTN})[${i}]
        Scroll Element Into View    xpath=${btn}
        Click Element    xpath=${btn}

        # --- Wait Show Modal ---
        Wait Until Element Is Visible
        ...    xpath=//div[contains(@class,'modal')]//h5[normalize-space(.)='Data Unggahan Dokumen']
        ...    1000s
        Log    Siswa ${nama_siswa}

        # --- Permohonan ---
        ${file_permohonan}=    Set Variable    C:/Users/sdnte/Downloads/KJP/${nama_siswa}_2.pdf
        Choose File
        ...    xpath=//input[@placeholder='File Permohonan Bansos']
        ...    ${file_permohonan}
        Sleep   1s
        Click Element    xpath=//span[@id='permohonan-bansos-btn']
        Wait Until Element Does Not Contain
        ...    xpath=//div[contains(@class,'notify-alert')]
        ...    Upload Sukses
        ...    20s

        # --- Ketaatan ---
        ${file_ketaatan}=    Set Variable    C:/Users/sdnte/Downloads/KJP/${nama_siswa}_3.pdf
        Choose File
        ...    xpath=//input[@placeholder='File Ketaatan Pengguna']
        ...    ${file_ketaatan}
        Sleep   1s
        Click Element    xpath=//span[@id='ketaatan-pengguna-btn']
        Wait Until Element Does Not Contain
        ...    xpath=//div[contains(@class,'notify-alert')]
        ...    Upload Sukses
        ...    20s

        # Tutup modal
        Press Keys    None    ESC
        Wait Until Element Is Not Visible
        ...    xpath=//div[@id='jakedu-modal-xl']
        ...    30s
        Wait Until Element Is Visible    xpath=(//tbody[@id='data']//tr)[${i}]//td[3]    10s

    END

    Sleep  10s
