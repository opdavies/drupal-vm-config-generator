<?php

namespace DrupalVm\tests\Command\Config;

use DrupalVm\tests\Command\FileGeneratorCommandTest;

class GenerateCommandTest extends FileGeneratorCommandTest
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->filename = 'config.yml';
    }

    public function testNoOptions()
    {
        $output = $this->runCommand('bin/drupalvm config:generate');

        $this->assertContains("{$this->filename} created", $output);

        $this->assertTrue($this->fs->exists($this->filename));
        $this->assertFileContains($this->filename, '# Created by the Drupal VM CLI (https://github.com/opdavies/drupal-vm-generator).');
    }

    public function testMachineNameOption()
    {
        $this->runCommand('bin/drupalvm config:generate --machine-name=foo');

        $this->assertFileContains($this->filename, 'vagrant_machine_name: foo');
    }

    public function testHostnameOption()
    {
        $this->runCommand('bin/drupalvm config:generate --hostname=foo');

        $this->assertFileContains($this->filename, 'vagrant_hostname: foo');
    }

    public function testIpAddressOption()
    {
        $this->runCommand('bin/drupalvm config:generate --ip-address=1.2.3.4');

        $this->assertFileContains($this->filename, 'vagrant_ip: "1.2.3.4"');
    }

    public function testCpusOption()
    {
        $this->runCommand('bin/drupalvm config:generate --cpus=2');

        $this->assertFileContains($this->filename, 'vagrant_cpus: 2');
    }

    public function testMemoryOption()
    {
        $this->runCommand('bin/drupalvm config:generate --memory=1024');

        $this->assertFileContains($this->filename, 'vagrant_memory: 1024');
    }

    public function testWebServerOption()
    {
        // Apache.
        $this->runCommand('bin/drupalvm config:generate --webserver=apache');

        $this->assertFileContains($this->filename, 'drupalvm_webserver: apache');
        $this->assertFileContains($this->filename, 'apache_vhosts:');
        $this->assertFileNotContains($this->filename, 'drupalvm_webserver: nginx');

        // Nginx.
        $this->runCommand('bin/drupalvm config:generate --overwrite --webserver=nginx');

        $this->assertFileContains($this->filename, 'drupalvm_webserver: nginx');
        $this->assertFileContains($this->filename, 'nginx_hosts:');
        $this->assertFileNotContains($this->filename, 'drupalvm_webserver: apache');
    }

    public function testPathOption()
    {
        $this->runCommand('bin/drupalvm config:generate --path="./site"');

        $this->assertFileContains($this->filename, 'local_path: ./site');
    }

    public function testDatabaseOptions()
    {
        $this->runCommand('bin/drupalvm config:generate --database-name=foo --database-user=bar --database-password=baz');

        $output = <<<EOF
drupal_mysql_user: bar
drupal_mysql_password: baz
drupal_mysql_database: foo
EOF;

        $this->assertFileContains($this->filename, $output);
    }

    public function testInstalledExtrasOption()
    {
        $this->runCommand('bin/drupalvm config:generate --installed-extras=adminer,xdebug');

        $output = <<<EOF
installed_extras:
  - adminer
  - xdebug
EOF;

        $this->assertFileContains($this->filename, $output);
        $this->assertFileNotContains($this->filename, 'installed_extras: []');
    }

    public function testNoDashboardOption()
    {
        // Apache.
        $this->runCommand('bin/drupalvm config:generate --webserver=apache');

        $this->assertFileContains($this->filename, 'serveralias: "dashboard.{{ vagrant_hostname }}"');
        $this->assertFileContains($this->filename, 'dashboard_install_dir: /var/www/dashboard');

        $this->runCommand('bin/drupalvm config:generate --overwrite --webserver=apache --no-dashboard');

        $this->assertFileNotContains($this->filename, 'serveralias: "dashboard.{{ vagrant_hostname }}"');
        $this->assertFileNotContains($this->filename, 'dashboard_install_dir: /var/www/dashboard');

        // Nginx.
        $this->runCommand('bin/drupalvm config:generate --overwrite --webserver=nginx');

        $this->assertFileContains($this->filename, 'server_name: "{{ vagrant_ip }} dashboard.{{ vagrant_hostname }}"');
        $this->assertFileContains($this->filename, 'dashboard_install_dir: /var/www/dashboard');

        $this->runCommand('bin/drupalvm config:generate --overwrite --webserver=nginx --no-dashboard');

        $this->assertFileNotContains($this->filename, 'server_name: "{{ vagrant_ip }} dashboard.{{ vagrant_hostname }}"');
        $this->assertFileNotContains($this->filename, 'dashboard_install_dir: /var/www/dashboard');
    }

    public function testNoCommentsOption()
    {
        $comment = <<<EOF
# `vagrant_box` can also be set to geerlingguy/centos6, geerlingguy/centos7,
# geerlingguy/ubuntu1204, parallels/ubuntu-14.04, etc.
EOF;

        $this->runCommand('bin/drupalvm config:generate');
        $this->assertFileContains($this->filename, $comment);

        $this->runCommand('bin/drupalvm config:generate --overwrite --no-comments');
        $this->assertFileNotContains($this->filename, $comment);
    }
}
