/!\ UNDER DEVELOPMENT /!\
/!\ DO NOT USE THIS PROJECT /!\

# MB/Dashboard

## Introduction
MBDashboard is a Symfony 3 bundle which provide integration and communication with some famous development code hosting and continuous integration tools.

The actual list of supported tools is:

| Code hosting | Status      |
|--------------|-------------|
| GitLab       | Working on  |
| GitHub       | Working on  |
| Stash        | Coming soon |

| Continuous integration | Status      |
|------------------------|-------------|
| GitLab-CI              | Coming soon |
| Bamboo                 | Coming soon |
| Jenkins                | Coming soon |

## Features
### 1. Multi sources
You can configure as much tool access as you want, allowing you to monitor some projects on GitHub as well as some projects on Stash, at the same time and on the same screen.

Example :

```YML
mb_dashboard:
    connections:

        # code hosting
        local_projects: { type: 'gitlab', host: 'https://my-super-gitlab.com', api_token: 'abcd1234' }
        public_projects: { type: 'github', host: 'https://api.github.com', api_token: '1234abcd' }
        old_projects: { type: 'gitlab', host: 'https://my-old-gitlab.com' api_token: 'another_token' }
        private_projects: { type: 'stash', host: 'https://my-super-stash.com' api_username: 'my_username', api_password: 'my_password' }

        # continuous integration
        local_ci: { type: 'gitlabci', host: 'https://my-super-gitlab-ci.com', api_token: '1a2b3c4d' }
        private_ci: { type: 'bamboo', host: 'https://my-super-bamboo.com', api_username: 'my_username', api_password: 'my_password' }
```

### 2. API
The DashboardBundle is able to communicate with all the tools listed previously, through the sames fonctions, no matter the type of destination tool. This allow to develop a custom dashboard easily without having to think if the code hosting tool is GitLab or Stash or anything else. Just go straight to the goal.

Example :

```PHP
/* @var $manager \MB\DashboardBundle\Manager\ProjectManager */
$manager = $this->get('mb_dashboard.project_manager');

$project = $this->getDoctrine()->getRepository('MBDashboardBundle:Project')->find(1);

$manager->refresh($project);
```

As simple as this. You don't have to care which api to use. We do it ourself internally. Just use the functions provided by the ```MB\DashboardBundle\Model\Connector\IConnector``` interface and rock your dashboard !

### 3. Hooks (coming soon)
If you don't want the run a synchronizing task which will perform a lot of queries on the remote tools, you can configure hooks on these services to update the dashboard in live.

Just configure the webhooks on the tools to call one of these URLs:

- /api/gitlab
- /api/github
- /api/stash
- /api/gitlabci
- /api/bamboo
- /api/jenkins

## 4. Actions (coming soon)
The Dashboard bundle does not only read information from remote tools, but it can interact with them too.

Examples:

```PHP
/* @var $manager \MB\DashboardBundle\Manager\ProjectManager */
$manager = $this->get('mb_dashboard.project_manager');

$project = $this->getDoctrine()->getRepository('MBDashboardBundle:Project')->find(1);

// Run the test units on the remote tool (eg: GitLab-CI)
$manager->performBuild($project);
```

```PHP
/* @var $manager \MB\DashboardBundle\Manager\ProjectManager */
$manager = $this->get('mb_dashboard.project_manager');

$project = $this->getDoctrine()->getRepository('MBDashboardBundle:Project')->find(1);

// Deploy the project from the remote tool to the selected deployment target (eg: Bamboo)
$deployments = $project->getDeploymentTargets();

$manager->deploy($project, $deployments['staging']);
```
