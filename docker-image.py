import os


docker_images = os.popen("docker images --format {{'.Repository'}}:{{'.Tag'}}").read()
ls = os.popen('ls *.tar').read()

choice = str(input("Load(L) , Save(S): "))
image = ''
list = []


def saving(choice, docker_images, image, list, counts=0):
    print(docker_images)

#Formating docker_images list and renaming

    for superate in docker_images:
        if superate != '\n':
            if superate == '/' or superate == ':':
                superate = '-'
                image = image + superate
            else:
                image = image + superate
        else:
            list.append(image)
            print(list)
            image = ''
            counts += 1
    print(f'{counts} Images')
    counts=0

    for image_name in list:
        counts += 1
        print(counts)
        print(f'Saving: {image_name}')
        save = f'docker save -o {image_name}.tar {image_name}'
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

    print(ls_list)
    for i in ls_list:
        print(i)
        print(f'Loading: {i}')
        os.popen(f'docker load -i {i}')


if choice == 'S' or choice == 's':
    saving(choice, docker_images, image, list)
elif choice == 'L' or choice == 'l':
    loading(ls)
else:
    print('Wrong Input')
