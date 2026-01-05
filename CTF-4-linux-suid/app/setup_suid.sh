#!/bin/bash
# Script de compilation du binaire SUID
# Ce script sera exécuté pendant le build Docker

echo "[*] Compiling SUID binary..."

gcc -o /usr/local/bin/check_system /tmp/check_system.c

if [ $? -eq 0 ]; then
    echo "[+] Compilation successful"
    
    # Configuration du binaire SUID
    chown root:ctfgroup /usr/local/bin/check_system
    chmod 4750 /usr/local/bin/check_system
    
    echo "[+] SUID bit set: root:ctfgroup with permissions 4750"
    ls -la /usr/local/bin/check_system
else
    echo "[-] Compilation failed"
    exit 1
fi

# Nettoyage
rm /tmp/check_system.c

echo "[*] Setup complete!"
