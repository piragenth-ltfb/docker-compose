import os


docker_images = os.popen("docker images --format {{'.Repository'}}:{{'.Tag'}}").read()
ls = os.popen('ls *.tar').read()

choice = str(input("Load(L) , Save(S): "))
image = ''
list = []


def saving(choice, docker_images, image, list, counts=0):
    print(os.system('docker images'))
#    print(docker_images)
    change_list = []
    num = 0
#Formating docker_images list and renaming
    change = ''
    for superate in docker_images:
        if superate != '\n':
                image = image + superate
                if superate == '/' or superate == ':':
                    new = '-'
                    change = change + new
                else:
                    change = change + superate
        else:
            list.append(image)
            change_list.append(change)
            change = ''
            image = ''
            counts += 1
#    print(list)
#    print(change_list)
    print(f'{counts} Images')
    counts=0
    for image_name in list:
        counts += 1
        print(counts)
        print(f'Saving: {image_name}')
        save = f'docker save -o {change_list[num]}.tar {image_name}'
        num += 1
#        print(save)
        os.system(save)


def loading(ls, ls_list=[], counts=0):
    print(ls)
    item = ''
    for seperate in ls:
        if seperate != '\n':
            item += seperate
        else:
            ls_list.append(item)
            item=''
            counts += 1
    print(f'{counts} Images found in this directory')
#    print(ls_list)
    for i in ls_list:
#        print(i)
        print(f'Loading: {i}')
        os.system(f'docker load -i {i}')
        print('\n')


if choice == 'S' or choice == 's':
    saving(choice, docker_images, image, list)
elif choice == 'L' or choice == 'l':
    loading(ls)
else:
    print('Wrong Input')
docker rmi $(docker images --filter "dangling=true" -q --no-trunc)   
