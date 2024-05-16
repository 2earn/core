<div>
    sending
</div>
@foreach($results as $result)
    <script>
        $(document).ready(function () {
            var user = "410"; // Remplacez 'user' par la clé appropriée de votre résultat
            var msg = "السلام عليكم.\n";
            msg += "سيدي/سيدتى  الكريم(ة) نوفيكم بصفتكم كمساهمون في شركة تو إيرن كاش بالمعلومات الآتية:\n";
            msg += "الإسم كاملا : {{ $result->name }}\n";
            msg += "معرفكم الوحيد في المنظومة هو : {{ $result->idUser }}\n";
            msg += "رقم هويتكم الوطنية: {{ $result->nationalID }}\n ";
            msg += "تمتلكون الآن عدد : {{ number_format($result->nbr_action,0) }} سهم"
            msg += "\n";
            msg += "التكلفة الجملية للأسهم: {{ $result->prix_action }} $";
            msg += "\n";
            msg +="التكلفة النهائية للسهم الواحد: {{ number_format($result->prix_unitaire,2) }} $ ";
            msg += "\n";
            msg += "ارباحكم الحالية من الأسهم :";
            msg += "{{number_format(getUserActualActionsProfit($result->idUser),2)}} $"
            msg += "\n";
            msg += "بريدكم الإلكتروني: {{ $result->email }}";
            msg += "\n";
            msg += "عنوانكم الوطني: ";
            msg += "{{ $result->adresse }}";
            msg += "\n";
            msg += "الرجاء استكمال ملفكم الشخصي إذا كان غير مكتمل.";
            msg += "\n";
            msg += "تمنياتنا لكم بالتوفيق";

            $.ajax({
                url: "{{ route('sendSMS') }}",
                type: "POST",

                data: {
                    user: user,
                    msg: msg,
                    "_token": "{{ csrf_token() }}"
                },
                success: function (data) {
                    console.log("success");
                }

            });

            console.log(msg);


        });
    </script>
@endforeach
