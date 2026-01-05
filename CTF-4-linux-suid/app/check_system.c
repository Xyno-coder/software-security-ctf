/*Binaire SUID vulnérable pour CTF-4*/

#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

int main() {
    printf("===========================================\n");
    printf("  System Health Check Tool v1.0\n");
    printf("  Running security diagnostics...\n");
    printf("===========================================\n\n");

    printf("[*] Current effective UID: %d\n", geteuid());
    printf("[*] Current real UID: %d\n\n", getuid());

    // Ceci permet au shell lancé par system() de garder les privilèges root
    setuid(0);
    setgid(0);
    
    // Création de chemin vulnerables  (appel de commande sans chemin absolu,Un attaquant peut manipuler PATH pour exécuter son propre "whoami")
    printf("[1] Checking current user...\n");
    system("whoami");
    
    printf("\n[2] Checking system status...\n");
    system("id");
    
    printf("\n[3] Listing important files...\n");
    system("ls -la /var/log 2>/dev/null | head -5");
    
    printf("\n===========================================\n");
    printf("  Diagnostic complete!\n");
    printf("===========================================\n");
    
    return 0;
}
